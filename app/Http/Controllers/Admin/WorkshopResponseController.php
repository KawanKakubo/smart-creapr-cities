<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WorkshopResponse;
use Illuminate\Http\Request;

class WorkshopResponseController extends Controller
{
    public function index(Request $request)
    {
        $session = $request->query('session', '');
        $responses = WorkshopResponse::query()
            ->when(in_array($session, ['manha', 'tarde'], true), fn ($query) => $query->where('session', $session))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $all = WorkshopResponse::all();
        $visible = $all->when(in_array($session, ['manha', 'tarde'], true), fn ($rows) => $rows->where('session', $session));
        $recentResponses = $visible->sortByDesc('created_at')->take(12);
        $sessionCounts = ['manha' => $all->where('session', 'manha')->count(), 'tarde' => $all->where('session', 'tarde')->count()];
        $municipalityCount = $all->pluck('municipality')->unique(fn ($name) => mb_strtolower($name))->count();
        $visibleMunicipalityCount = $visible->pluck('municipality')->unique(fn ($name) => mb_strtolower($name))->count();
        $partners = $visible->where('session', 'manha')->flatMap(fn ($response) => $response->answers['partners'] ?? [])->countBy()->sortDesc()->take(8);
        $governanceCounts = collect(['Corporativa', 'Dados', 'Responsiva', 'Colaborativa', 'Multinível', 'Policêntrica'])
            ->mapWithKeys(fn ($name) => [$name => $all->where('session', 'manha')->filter(fn ($response) => in_array($name, $response->answers['maturity'] ?? [], true))->count()]);
        $axisCounts = $visible->where('session', 'manha')
            ->map(fn ($response) => $response->answers['axis'] ?? null)
            ->filter()->countBy()->sortDesc();
        $trendCounts = $visible->where('session', 'tarde')
            ->flatMap(fn ($response) => array_filter([$response->answers['trend_one'] ?? null, $response->answers['trend_two'] ?? null]))
            ->countBy()->sortDesc();

        return view('admin.workshop.index', compact('responses', 'session', 'sessionCounts', 'municipalityCount', 'visibleMunicipalityCount', 'recentResponses', 'partners', 'governanceCounts', 'axisCounts', 'trendCounts'));
    }

    public function export(Request $request)
    {
        $query = WorkshopResponse::query()->when(in_array($request->query('session'), ['manha', 'tarde'], true), fn ($q) => $q->where('session', $request->query('session')))->latest();
        $filename = 'respostas-oficina-smart-crea-cities.csv';

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Data', 'Turno', 'Município', 'Porte', 'Eixo', 'Desafio', 'Governança de dados', 'Governança responsiva', 'Governança corporativa', 'Governança colaborativa', 'Governança multinível', 'Governança policêntrica', 'Maturidades ativas', 'Alvo 3E', 'Indicador', 'Atores', 'Instrumento de governança', 'Ação 72h', 'Ação 30 dias', 'Megatendência 1', 'Megatendência 2', 'Sinal fraco', 'Futuro projetado', 'Futuro de ruptura', 'Futuro preferível', 'Marco 2030', 'Marco 2027', 'Piloto 90 dias', 'Continuidade'], ';');
            $query->chunk(200, function ($responses) use ($out) {
                foreach ($responses as $response) {
                    $a = $response->answers;
                    fputcsv($out, [$response->created_at->format('d/m/Y H:i'), $response->session, $response->municipality, $response->population_band, $a['axis'] ?? '', $a['challenge'] ?? '', $a['gov_data'] ?? '', $a['gov_responsive'] ?? '', $a['gov_corporate'] ?? '', $a['gov_collaborative'] ?? '', $a['gov_multilevel'] ?? '', $a['gov_polycentric'] ?? '', implode(', ', $a['maturity'] ?? []), $a['target_3e'] ?? '', $a['data_indicator'] ?? '', implode(', ', $a['partners'] ?? []), $a['governance'] ?? '', $a['action_72h'] ?? '', $a['action_30d'] ?? '', $a['trend_one'] ?? '', $a['trend_two'] ?? '', $a['weak_signal'] ?? '', $a['projected_future'] ?? '', $a['disruption_future'] ?? '', $a['preferred_future'] ?? '', $a['milestone_2030'] ?? '', $a['milestone_2027'] ?? '', $a['pilot_90d'] ?? '', $a['continuity'] ?? ''], ';');
                }
            });
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
