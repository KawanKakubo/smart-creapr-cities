<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'protocolo',
        'access_token',
        'token_expires_at',
        'user_id',
        // Dados do Município
        'municipio_nome',
        // Novos campos para fluxo simplificado
        'setores_economicos',
        'regional_creapr',
        'faz_parte_mais_engenharia',
        'pontuacao_estimulo',
        'pontuacao_educacao',
        'pontuacao_estruturas',
        'diagnostico_estimulo_iniciado_em',
        'diagnostico_estimulo_concluido_em',
        'diagnostico_educacao_iniciado_em',
        'diagnostico_educacao_concluido_em',
        'diagnostico_estruturas_iniciado_em',
        'diagnostico_estruturas_concluido_em',
        // Dados do Responsável
        'responsavel_nome',
        'responsavel_cpf',
        'responsavel_telefone',
        'responsavel_email',
        'responsavel_orgao',
        'responsavel_funcao',
        'orgao_endereco',
        // Dados do Prefeito
        'prefeito_nome',
        'prefeito_cpf',
        'prefeito_telefone',
        'prefeito_mandato',
        'habitantes_num',
        // Políticas Públicas
        'possui_lei_inovacao',
        'link_lei_inovacao',
        'possui_politica_governo_digital',
        'link_evidencia_governo_digital',
        'realizou_cpsi',
        'link_evidencia_cpsi',
        'possui_masterplan',
        'link_evidencia_masterplan',
        'possui_plano_diretor_smart',
        'link_evidencia_plano_diretor',
        'possui_politica_sandbox',
        'link_evidencia_sandbox',
        'possui_fundo_inovacao',
        'cnpj_fundo_inovacao',
        'possui_conselho_cti',
        'link_portaria_conselho',
        'possui_normativa_governanca',
        'link_normativa_governanca',
        'possui_secretaria_cti',
        'orgao_responsavel_cti',
        'rodou_contrato_solucao_inovadora',
        'link_evidencia_contrato',
        'possui_politica_living_lab',
        'link_evidencia_living_lab',
        'possui_estrategia_transformacao_digital',
        'link_evidencia_estrategia',
        // Secretarias
        'secretaria_inovacao',
        'secretaria_tecnologia_smart',
        'secretaria_engenharia',
        // Gestão e Tecnologia
        'conhece_comunidade_inteligente',
        'usa_ferramenta_gestao_identidades',
        'possui_normativa_lgpd',
        'link_evidencia_lgpd',
        'dpo_nome',
        'dpo_contato',
        'utiliza_sistemas_interoperaveis',
        // Educação
        'escolas_fundamental_inicial_municipal',
        'escolas_fundamental_final_municipal',
        'escolas_fundamental_final_estadual',
        'escolas_ensino_medio_estadual',
        'realizou_hackathons_educacao',
        'link_evidencia_hackathons_educacao',
        'tem_universidade_presencial',
        'universidades_lista',
        'aplica_novos_letramentos',
        // Ecossistema
        'startups_num',
        'ambientes_inovacao',
        'hackathons_realizados',
        // Planejamento
        'possui_planejamento_estrategico',
        'link_evidencia_planejamento',
        'relevancia_engenharias',
        'relevancia_engenharias_descricao',
        'ganhou_premio_inovacao',
        'descricao_premio_relevante',
        // Compromisso
        'disposto_dedicar_tempo',
        'comite_participantes',
        'carta_compromisso_path',
        // Ponto Focal
        'ponto_focal_nome',
        'ponto_focal_cargo',
        'ponto_focal_email',
        'ponto_focal_telefone',
        'ponto_focal_celular',
        'declaracao_interesse',
        // Status
        'status',
        'status_observacao',
        'aprovado_em',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'token_expires_at' => 'datetime',
        'aprovado_em' => 'datetime',
        // Novos timestamps de diagnóstico
        'diagnostico_estimulo_iniciado_em' => 'datetime',
        'diagnostico_estimulo_concluido_em' => 'datetime',
        'diagnostico_educacao_iniciado_em' => 'datetime',
        'diagnostico_educacao_concluido_em' => 'datetime',
        'diagnostico_estruturas_iniciado_em' => 'datetime',
        'diagnostico_estruturas_concluido_em' => 'datetime',
        // Booleans
        'faz_parte_mais_engenharia' => 'boolean',
        'possui_lei_inovacao' => 'boolean',
        'possui_politica_governo_digital' => 'boolean',
        'realizou_cpsi' => 'boolean',
        'possui_masterplan' => 'boolean',
        'possui_plano_diretor_smart' => 'boolean',
        'possui_fundo_inovacao' => 'boolean',
        'possui_conselho_cti' => 'boolean',
        'possui_normativa_governanca' => 'boolean',
        'possui_secretaria_cti' => 'boolean',
        'rodou_contrato_solucao_inovadora' => 'boolean',
        'possui_politica_sandbox' => 'boolean',
        'possui_politica_living_lab' => 'boolean',
        'possui_estrategia_transformacao_digital' => 'boolean',
        'conhece_comunidade_inteligente' => 'boolean',
        'usa_ferramenta_gestao_identidades' => 'boolean',
        'possui_normativa_lgpd' => 'boolean',
        'utiliza_sistemas_interoperaveis' => 'boolean',
        'realizou_hackathons_educacao' => 'boolean',
        'tem_universidade_presencial' => 'boolean',
        'possui_planejamento_estrategico' => 'boolean',
        'ganhou_premio_inovacao' => 'boolean',
        'disposto_dedicar_tempo' => 'boolean',
        'declaracao_interesse' => 'boolean',
        // Arrays
        'setores_economicos' => 'array',
        'ambientes_inovacao' => 'array',
        'hackathons_realizados' => 'array',
        // Integers
        'habitantes_num' => 'integer',
        'pontuacao_estimulo' => 'integer',
        'pontuacao_educacao' => 'integer',
        'pontuacao_estruturas' => 'integer',
        'startups_num' => 'integer',
        'escolas_fundamental_inicial_municipal' => 'integer',
        'escolas_fundamental_final_municipal' => 'integer',
        'escolas_fundamental_final_estadual' => 'integer',
        'escolas_ensino_medio_estadual' => 'integer',
    ];
    
    /**
     * Relacionamento com User
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    /**
     * Relacionamento com DiagnosticAnswers
     */
    public function diagnosticAnswers()
    {
        return $this->hasMany(DiagnosticAnswer::class);
    }

    /**
     * Relacionamento com CommitteeMembers
     */
    public function committeeMembers()
    {
        return $this->hasMany(CommitteeMember::class);
    }

    /**
     * Calcula a pontuação total (soma dos 3 E's)
     */
    public function getTotalScore()
    {
        return ($this->pontuacao_estimulo ?? 0) + 
               ($this->pontuacao_educacao ?? 0) + 
               ($this->pontuacao_estruturas ?? 0);
    }

    /**
     * Verifica se todos os diagnósticos foram concluídos
     */
    public function allDiagnosticsCompleted()
    {
        return $this->diagnostico_estimulo_concluido_em !== null &&
               $this->diagnostico_educacao_concluido_em !== null &&
               $this->diagnostico_estruturas_concluido_em !== null;
    }

    /**
     * Verifica se pode acessar o diagnóstico (precisa estar aprovado)
     */
    public function canAccessDiagnostic()
    {
        return $this->isApproved();
    }

    /**
     * Verifica se está participando do "Mais Engenharia"
     */
    public function isPartOfMaisEngenharia()
    {
        return $this->faz_parte_mais_engenharia === true;
    }
    
    /**
     * Verifica se a manifestação foi aprovada
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }
    
    /**
     * Verifica se a manifestação está pendente
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }
    
    /**
     * Verifica se está em análise
     */
    public function isUnderReview()
    {
        return $this->status === 'under_review';
    }
    
    /**
     * Verifica se foi rejeitado
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }

    /**
     * Scope para filtrar municípios ativos (is_active = true ou NULL)
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->where('is_active', true)->orWhereNull('is_active');
        });
    }
}
