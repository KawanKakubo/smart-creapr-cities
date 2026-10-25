@extends('layouts.admin-presentation')

@section('title', 'Painel ao vivo | Smart Crea-PR Cities')

@section('content')
@php
    $responseTotal = $session === 'manha' ? $sessionCounts['manha'] : ($session === 'tarde' ? $sessionCounts['tarde'] : array_sum($sessionCounts));
    $activeBars = $session === 'tarde' ? $trendCounts : $axisCounts;
    $barMax = max(1, (int) ($activeBars->max() ?? 0));
    $detailLabels = [
        'axis' => 'Eixo principal',
        'gov_data' => 'Governança de dados',
        'gov_responsive' => 'Governança responsiva',
        'gov_corporate' => 'Governança corporativa',
        'gov_collaborative' => 'Governança colaborativa',
        'gov_multilevel' => 'Governança multinível',
        'gov_polycentric' => 'Governança policêntrica',
        'target_3e' => 'Alvo principal (3E)',
        'data_indicator' => 'Indicador',
        'governance' => 'Instrumento ou instância de governança',
        'action_72h' => 'Ação nas primeiras 72 horas',
        'action_30d' => 'Marco de 30 dias',
        'trend_one' => 'Megatendência 1',
        'trend_two' => 'Megatendência 2',
        'weak_signal' => 'Sinal fraco local',
        'projected_future' => 'Futuro projetado',
        'disruption_future' => 'Futuro de ruptura',
        'preferred_future' => 'Futuro preferível em 2036',
        'milestone_2030' => 'Marco de 2030',
        'milestone_2027' => 'Marco de 2027',
        'pilot_90d' => 'Piloto de 90 dias',
        'continuity' => 'Continuidade do plano',
        'maturity' => 'Governanças já praticadas',
        'partners' => 'Atores mobilizados',
    ];
@endphp
<main class="min-h-screen bg-slate-50">
    <header class="border-b border-slate-200 bg-white/95 backdrop-blur-xl">
        <div class="mx-auto flex max-w-[1500px] flex-wrap items-center justify-between gap-4 px-5 py-4 lg:px-8">
            <div class="flex items-center gap-3">
                <div class="grid h-11 w-11 place-items-center rounded-2xl bg-gradient-to-br from-cyan-300 to-indigo-500 text-lg font-black text-slate-950">S</div>
                <div><p class="text-[10px] font-extrabold uppercase tracking-[.24em] text-cyan-700">CREA-PR · capacitação municipal</p><p class="text-sm font-bold text-slate-900">Smart Crea Cities <span class="font-normal text-slate-500">/ Painel ao vivo</span></p></div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-bold text-emerald-800"><span class="relative flex h-2 w-2"><span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-70"></span><span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"></span></span> AO VIVO</span>
                <a href="{{ route('workshop.show') }}" target="_blank" class="rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100">Abrir formulário ↗</a>
                <button type="button" onclick="toggleFullscreen()" class="rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100">⛶ Tela cheia</button>
                <button type="button" id="pauseRefresh" onclick="toggleRefresh()" class="rounded-xl border border-slate-200 px-3.5 py-2.5 text-xs font-bold text-slate-700 transition hover:bg-slate-100">Ⅱ Pausar atualização</button>
                <a href="{{ route('admin.workshop.export', request()->only('session')) }}" class="rounded-xl bg-slate-900 px-3.5 py-2.5 text-xs font-extrabold text-white transition hover:bg-slate-700">Exportar CSV ↓</a>
                <form method="POST" action="{{ route('admin.logout') }}">@csrf<button class="px-2 py-2.5 text-xs font-semibold text-slate-600 hover:text-slate-900">Sair</button></form>
            </div>
        </div>
    </header>

    <div class="mx-auto max-w-[1500px] px-5 pb-12 pt-7 lg:px-8">
        <section class="grid items-end gap-6 lg:grid-cols-[1fr_auto]">
            <div><p class="text-xs font-bold uppercase tracking-[.25em] text-indigo-700">Painel de participação · Paraná</p><h1 class="mt-2 max-w-4xl text-3xl font-black leading-tight tracking-tight text-slate-900 sm:text-5xl">As cidades estão <span class="bg-gradient-to-r from-cyan-700 via-blue-700 to-violet-700 bg-clip-text text-transparent">construindo ideias</span> juntas.</h1><p class="mt-3 max-w-2xl text-sm text-slate-600 sm:text-base">Respostas da oficina de meta-governança e gestão antecipatória, consolidadas para a conversa no telão.</p></div>
            <div class="rounded-2xl border border-slate-200 bg-slate-100 p-1.5">
                <div class="flex gap-1" role="group" aria-label="Filtrar respostas por turno">
                    <a href="{{ route('admin.workshop.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $session==='' ? 'bg-white text-slate-950 shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">Todos</a>
                    <a href="{{ route('admin.workshop.index', ['session'=>'manha']) }}" class="rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $session==='manha' ? 'bg-cyan-500 text-slate-950 shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">☀ Manhã</a>
                    <a href="{{ route('admin.workshop.index', ['session'=>'tarde']) }}" class="rounded-xl px-4 py-2.5 text-sm font-bold transition {{ $session==='tarde' ? 'bg-violet-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">◐ Tarde</a>
                </div>
            </div>
        </section>

        <div id="liveData" aria-live="polite">
        <section class="mt-7 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <article class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm p-5"><div class="absolute -right-5 -top-8 h-24 w-24 rounded-full bg-cyan-400/10 blur-2xl"></div><p class="text-xs font-bold uppercase tracking-wider text-slate-600">Contribuições recebidas</p><div class="mt-2 flex items-end gap-2"><span class="text-4xl font-black tabular-nums text-slate-900">{{ $responseTotal }}</span><span class="pb-1 text-xs text-slate-500">{{ $session === 'manha' ? 'turno da manhã' : ($session === 'tarde' ? 'turno da tarde' : 'nos dois turnos') }}</span></div></article>
            <article class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5"><p class="text-xs font-bold uppercase tracking-wider text-slate-600">Cidades participantes</p><div class="mt-2 flex items-end gap-2"><span class="text-4xl font-black tabular-nums text-slate-900">{{ $visibleMunicipalityCount }}</span><span class="pb-1 text-xs text-slate-500">nesta seleção</span></div></article>
            <article class="rounded-2xl border border-cyan-200 bg-cyan-50 p-5"><p class="text-xs font-bold uppercase tracking-wider text-cyan-800">Manhã · meta-governança</p><div class="mt-2 flex items-end justify-between"><span class="text-4xl font-black tabular-nums text-cyan-800">{{ $sessionCounts['manha'] }}</span><span class="text-xs text-cyan-700">respostas</span></div></article>
            <article class="rounded-2xl border border-violet-200 bg-violet-50 p-5"><p class="text-xs font-bold uppercase tracking-wider text-violet-800">Tarde · futuros 2036</p><div class="mt-2 flex items-end justify-between"><span class="text-4xl font-black tabular-nums text-violet-800">{{ $sessionCounts['tarde'] }}</span><span class="text-xs text-violet-700">respostas</span></div></article>
        </section>

        <section class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1.5fr)_minmax(320px,.8fr)]">
            <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-md shadow-slate-200/70 sm:p-7">
                <div class="flex flex-wrap items-start justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-[.2em] {{ $session==='tarde' ? 'text-violet-700' : 'text-cyan-700' }}">{{ $session==='tarde' ? 'Radar de futuros' : 'Nuvem de desafios' }}</p><h2 class="mt-1 text-2xl font-extrabold text-slate-900">{{ $session==='tarde' ? 'O que pode transformar o Paraná?' : 'O que as cidades querem resolver?' }}</h2><p class="mt-1 text-sm text-slate-600">{{ $session==='tarde' ? 'Megatendências escolhidas pelas cidades.' : 'Eixos que concentram os desafios municipais.' }}</p></div><span class="rounded-full border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-600">{{ $activeBars->sum() }} seleções</span></div>
                <div class="mt-7 space-y-5">
                    @forelse($activeBars as $label => $count)
                        <div><div class="mb-2 flex items-center justify-between gap-4"><span class="text-sm font-semibold text-slate-800">{{ $label }}</span><span class="text-sm font-black tabular-nums {{ $session==='tarde' ? 'text-violet-800' : 'text-cyan-800' }}">{{ $count }} <span class="font-medium text-slate-500">{{ $count===1 ? 'cidade' : 'cidades' }}</span></span></div><div class="h-3 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full {{ $session==='tarde' ? 'bg-gradient-to-r from-violet-500 to-fuchsia-300' : 'bg-gradient-to-r from-cyan-500 to-blue-300' }} transition-all duration-700" style="width: {{ max(4, round($count / $barMax * 100)) }}%"></div></div></div>
                    @empty
                        <div class="grid min-h-44 place-items-center rounded-2xl border border-dashed border-slate-200 text-center"><div><div class="text-3xl">✳</div><p class="mt-2 font-bold text-slate-600">As primeiras ideias aparecem aqui</p><p class="mt-1 text-sm text-slate-500">Compartilhe o link do formulário com as cidades.</p></div></div>
                    @endforelse
                </div>
            </article>

            @if($session !== 'tarde')
                <article class="rounded-3xl border border-slate-200 bg-white p-5 shadow-md shadow-slate-200/70 sm:p-7"><div><p class="text-xs font-extrabold uppercase tracking-[.2em] text-amber-700">Radar de maturidade</p><h2 class="mt-1 text-xl font-extrabold text-slate-900">As seis governanças</h2><p class="mt-1 text-sm text-slate-600">Dimensões já praticadas pelas cidades.</p></div><div class="mt-6 space-y-4">@foreach($governanceCounts as $name => $count)<div><div class="mb-1.5 flex justify-between text-xs"><span class="font-semibold text-slate-700">{{ $name }}</span><span class="font-bold tabular-nums text-amber-800">{{ $count }}</span></div><div class="h-2 overflow-hidden rounded-full bg-slate-200"><div class="h-full rounded-full bg-gradient-to-r from-amber-500 to-yellow-400" style="width: {{ min(100, round($count / max(1, $sessionCounts['manha']) * 100)) }}%"></div></div></div>@endforeach</div><div class="mt-6 border-t border-slate-200 pt-5"><p class="text-xs font-bold uppercase tracking-wider text-slate-600">Atores mais mobilizados</p><div class="mt-3 flex flex-wrap gap-2">@forelse($partners as $name => $count)<span class="rounded-full border border-cyan-200 bg-cyan-50 px-3 py-1.5 text-xs text-cyan-800">{{ $name }} <b class="ml-1 text-slate-900">{{ $count }}</b></span>@empty<span class="text-sm text-slate-500">Aguardando respostas da manhã.</span>@endforelse</div></div></article>
            @else
                <article class="rounded-3xl border border-violet-200 bg-gradient-to-br from-violet-50 to-white p-6 sm:p-7"><p class="text-xs font-extrabold uppercase tracking-[.2em] text-violet-800">Cone de Voros · 2036</p><h2 class="mt-2 text-2xl font-black text-slate-900">Do cenário à ação</h2><p class="mt-3 text-sm leading-relaxed text-violet-700">As respostas conectam sinais fracos e rupturas possíveis a uma visão preferível para cada município.</p><div class="mt-6 grid grid-cols-3 gap-2 text-center"><div class="rounded-2xl bg-white p-3 shadow-sm"><p class="text-2xl font-black text-violet-800">2036</p><p class="mt-1 text-[10px] uppercase tracking-wider text-slate-600">Visão</p></div><div class="rounded-2xl bg-white p-3 shadow-sm"><p class="text-2xl font-black text-fuchsia-800">2030</p><p class="mt-1 text-[10px] uppercase tracking-wider text-slate-600">Marco</p></div><div class="rounded-2xl bg-white p-3 shadow-sm"><p class="text-2xl font-black text-cyan-800">90d</p><p class="mt-1 text-[10px] uppercase tracking-wider text-slate-600">Piloto</p></div></div></article>
            @endif
        </section>

        <section class="mt-7">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-3"><div><p class="text-xs font-extrabold uppercase tracking-[.2em] text-emerald-700">Mural colaborativo</p><h2 class="mt-1 text-2xl font-extrabold text-slate-900">Ideias que chegaram agora</h2><p class="mt-1 text-sm text-slate-600">Desafios compartilhados pelas cidades, em ordem de envio.</p></div><span class="text-xs text-slate-500">Exibindo {{ $recentResponses->count() }} contribuições recentes</span></div>
            @if($recentResponses->isEmpty())
                <div class="grid min-h-48 place-items-center rounded-3xl border border-dashed border-slate-300 bg-white text-center"><div><div class="text-4xl">💬</div><p class="mt-3 font-bold text-slate-700">O mural está pronto para receber as cidades</p><p class="mt-1 text-sm text-slate-500">As contribuições aparecem aqui assim que forem enviadas.</p><a href="{{ route('workshop.show') }}" class="mt-4 inline-flex rounded-xl bg-cyan-500 px-4 py-2.5 text-sm font-extrabold text-slate-950">Abrir formulário da oficina</a></div></div>
            @else
                <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($recentResponses as $response)
                        @php($a = $response->answers)
                        <article class="group rounded-2xl border border-slate-200 bg-white p-5 transition hover:-translate-y-0.5 hover:border-cyan-200/30 hover:bg-slate-200"><div class="flex items-center justify-between gap-2"><div class="flex min-w-0 items-center gap-2"><span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl {{ $response->session==='tarde' ? 'bg-violet-100 text-violet-800' : 'bg-cyan-100 text-cyan-800' }} text-sm font-black">{{ mb_substr($response->municipality, 0, 1) }}</span><div class="min-w-0"><h3 class="truncate font-extrabold text-slate-900">{{ $response->municipality }}</h3><p class="text-[11px] text-slate-500">{{ $response->population_band }} hab. · {{ $response->created_at->format('H:i') }}</p></div></div><span class="rounded-full px-2.5 py-1 text-[10px] font-bold {{ $response->session==='tarde' ? 'bg-violet-100 text-violet-800' : 'bg-cyan-100 text-cyan-800' }}">{{ $response->session==='tarde' ? 'TARDE' : 'MANHÃ' }}</span></div><p class="mt-4 text-sm leading-relaxed text-slate-700">“{{ $a['challenge'] ?? '—' }}”</p><div class="mt-4 border-t border-slate-200 pt-3">@if($response->session==='tarde')<p class="text-xs font-bold text-violet-800">VISÃO 2036</p><p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-600">{{ $a['preferred_future'] ?? $a['pilot_90d'] ?? 'A cidade está estruturando seu futuro.' }}</p>@else<p class="text-xs font-bold text-cyan-800">PRÓXIMO PASSO · 72H</p><p class="mt-1 line-clamp-2 text-xs leading-relaxed text-slate-600">{{ $a['action_72h'] ?? $a['target_3e'] ?? 'A cidade está desenhando sua rede.' }}</p>@endif</div></article>
                    @endforeach
                </div>
            @endif
        </section>

        <details class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white">
            <summary class="cursor-pointer list-none px-5 py-4 text-sm font-bold text-slate-600 hover:bg-slate-100">Abrir respostas completas <span class="ml-1 text-slate-500">({{ $responses->total() }})</span><span class="float-right text-slate-500">＋</span></summary>
            <div class="border-t border-slate-200">@forelse($responses as $response)@php($a = $response->answers)<article class="border-b border-slate-200 p-5 last:border-b-0"><div class="flex flex-wrap items-center gap-2"><h3 class="font-bold text-slate-900">{{ $response->municipality }}</h3><span class="text-xs text-slate-500">{{ $response->population_band }} · {{ $response->session==='manha' ? 'Manhã' : 'Tarde' }} · {{ $response->created_at->format('d/m/Y H:i') }}</span></div><p class="mt-2 text-sm text-slate-600"><b>Desafio:</b> {{ $a['challenge'] ?? '—' }}</p><div class="mt-3 grid gap-2 text-xs leading-relaxed text-slate-600 sm:grid-cols-2">@foreach($a as $key => $value)@php($displayValue = is_array($value) ? implode(', ', $value) : $value)@if($key !== 'challenge' && filled($displayValue))<p><b class="text-slate-700">{{ $detailLabels[$key] ?? str($key)->replace('_', ' ')->title() }}:</b> {{ $displayValue }}</p>@endif @endforeach</div></article>@empty<div class="p-8 text-sm text-slate-500">Nenhuma contribuição nesta seleção.</div>@endforelse</div>
            <div class="border-t border-slate-200 px-5 py-4">{{ $responses->links() }}</div>
        </details>
        </div>
        <footer class="mt-6 flex flex-wrap justify-between gap-2 text-xs text-slate-600"><span>Smart Crea-PR Cities · Painel de facilitação</span><span id="syncStatus">Atualiza automaticamente a cada 8 segundos</span></footer>
    </div>
</main>
<script>
    let refreshPaused = false;
    let refreshInFlight = false;
    function toggleFullscreen() {
        if (!document.fullscreenElement) document.documentElement.requestFullscreen?.();
        else document.exitFullscreen?.();
    }
    function toggleRefresh() {
        refreshPaused = !refreshPaused;
        document.getElementById('pauseRefresh').textContent = refreshPaused ? '▶ Retomar atualização' : 'Ⅱ Pausar atualização';
        document.getElementById('syncStatus').textContent = refreshPaused ? 'Atualização automática pausada' : 'Atualiza automaticamente a cada 8 segundos';
    }
    async function refreshPanel() {
        if (refreshPaused || refreshInFlight) return;
        refreshInFlight = true;
        try {
            const response = await fetch(window.location.href, { cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            if (!response.ok) throw new Error('Falha ao carregar atualizações');
            const markup = await response.text();
            const nextDocument = new DOMParser().parseFromString(markup, 'text/html');
            const currentContent = document.getElementById('liveData');
            const nextContent = nextDocument.getElementById('liveData');
            if (!currentContent || !nextContent) throw new Error('Painel atualizado indisponível');
            const wasExpanded = currentContent.querySelector('details')?.open ?? false;
            currentContent.replaceWith(nextContent);
            if (wasExpanded) nextContent.querySelector('details')?.setAttribute('open', 'open');
            document.getElementById('syncStatus').textContent = `Atualizado às ${new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' })} · próxima atualização em 8 s`;
        } catch (error) {
            document.getElementById('syncStatus').textContent = 'Não foi possível atualizar agora. Tentaremos novamente em 8 s.';
        } finally {
            refreshInFlight = false;
        }
    }
    window.setInterval(refreshPanel, 8000);
</script>
@endsection
