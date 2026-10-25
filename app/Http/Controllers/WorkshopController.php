<?php

namespace App\Http\Controllers;

use App\Models\WorkshopResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkshopController extends Controller
{
    public function show()
    {
        return view('workshop.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'municipality' => ['required', 'string', 'max:120'],
            'population_band' => ['required', Rule::in(['Até 20 mil', '20 a 50 mil', '50 a 150 mil', 'Acima de 150 mil'])],
            'session' => ['required', Rule::in(['manha', 'tarde'])],
            'answers' => ['required', 'array'],
            'answers.challenge' => ['required', 'string', 'max:500'],
            'answers.axis' => ['nullable', 'string', 'max:160'],
            'answers.gov_data' => ['nullable', 'string', 'max:300'],
            'answers.gov_responsive' => ['nullable', 'string', 'max:300'],
            'answers.gov_corporate' => ['nullable', 'string', 'max:200'],
            'answers.gov_collaborative' => ['nullable', 'string', 'max:300'],
            'answers.gov_multilevel' => ['nullable', 'string', 'max:200'],
            'answers.gov_polycentric' => ['nullable', 'string', 'max:300'],
            'answers.maturity' => ['nullable', 'array', 'max:6'],
            'answers.maturity.*' => ['string', Rule::in(['Corporativa', 'Dados', 'Responsiva', 'Colaborativa', 'Multinível', 'Policêntrica'])],
            'answers.target_3e' => ['nullable', 'string', 'max:300'],
            'answers.data_indicator' => ['nullable', 'string', 'max:300'],
            'answers.partners' => ['nullable', 'array', 'max:10'],
            'answers.partners.*' => ['string', Rule::in([
                'Universidades e ICTs',
                'Sistema S (SENAI/SEBRAE)',
                'Setor produtivo / associações / CREA-PR',
                'Sociedade civil e conselhos',
                'Governo do Paraná / União',
                'Consórcio intermunicipal / cidades vizinhas',
            ])],
            'answers.governance' => ['nullable', 'string', 'max:200'],
            'answers.action_72h' => ['nullable', 'string', 'max:300'],
            'answers.action_30d' => ['nullable', 'string', 'max:300'],
            'answers.trend_one' => ['nullable', 'string', 'max:200'],
            'answers.trend_two' => ['nullable', 'string', 'max:200'],
            'answers.weak_signal' => ['nullable', 'string', 'max:300'],
            'answers.projected_future' => ['nullable', 'string', 'max:500'],
            'answers.disruption_future' => ['nullable', 'string', 'max:500'],
            'answers.preferred_future' => ['nullable', 'string', 'max:500'],
            'answers.milestone_2030' => ['nullable', 'string', 'max:300'],
            'answers.milestone_2027' => ['nullable', 'string', 'max:300'],
            'answers.pilot_90d' => ['nullable', 'string', 'max:500'],
            'answers.continuity' => ['nullable', 'string', 'max:500'],
        ]);

        WorkshopResponse::create([
            'municipality' => $validated['municipality'],
            'population_band' => $validated['population_band'],
            'session' => $validated['session'],
            'answers' => $validated['answers'],
        ]);

        return redirect()->route('workshop.show')->with('success', 'Contribuição registrada. Obrigado por compartilhar o plano da sua cidade!');
    }
}
