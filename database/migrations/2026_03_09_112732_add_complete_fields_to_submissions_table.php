<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Responsável pela Manifestação de Interesse
            $table->string('responsavel_nome')->nullable()->after('municipio_nome');
            $table->string('responsavel_cpf')->nullable()->after('responsavel_nome');
            $table->string('responsavel_telefone')->nullable()->after('responsavel_cpf');
            $table->string('responsavel_email')->nullable()->after('responsavel_telefone');
            $table->string('responsavel_orgao')->nullable()->after('responsavel_email');
            $table->string('responsavel_funcao')->nullable()->after('responsavel_orgao');
            $table->string('orgao_endereco')->nullable()->after('responsavel_funcao');
            
            // Dados do Prefeito (completos)
            $table->string('prefeito_cpf')->nullable()->after('prefeito_nome');
            $table->string('prefeito_telefone')->nullable()->after('prefeito_cpf');
            
            // Políticas Públicas Adicionais
            $table->boolean('possui_politica_governo_digital')->after('link_lei_inovacao')->default(false);
            $table->string('link_evidencia_governo_digital')->nullable()->after('possui_politica_governo_digital');
            
            $table->boolean('realizou_cpsi')->after('link_evidencia_governo_digital')->default(false);
            $table->string('link_evidencia_cpsi')->nullable()->after('realizou_cpsi');
            
            $table->boolean('possui_masterplan')->after('link_evidencia_cpsi')->default(false);
            $table->string('link_evidencia_masterplan')->nullable()->after('possui_masterplan');
            
            $table->boolean('possui_plano_diretor_smart')->after('link_evidencia_masterplan')->default(false);
            $table->string('link_evidencia_plano_diretor')->nullable()->after('possui_plano_diretor_smart');
            
            // Secretarias
            $table->string('secretaria_inovacao')->nullable()->after('link_evidencia_plano_diretor');
            $table->string('secretaria_tecnologia_smart')->nullable()->after('secretaria_inovacao');
            $table->string('secretaria_engenharia')->nullable()->after('secretaria_tecnologia_smart');
            
            // Comunidade Inteligente e Gestão
            $table->boolean('conhece_comunidade_inteligente')->after('secretaria_engenharia')->default(false);
            $table->boolean('usa_ferramenta_gestao_identidades')->after('conhece_comunidade_inteligente')->default(false);
            $table->boolean('possui_normativa_lgpd')->after('usa_ferramenta_gestao_identidades')->default(false);
            $table->string('link_evidencia_lgpd')->nullable()->after('possui_normativa_lgpd');
            $table->string('dpo_nome')->nullable()->after('link_evidencia_lgpd');
            $table->string('dpo_contato')->nullable()->after('dpo_nome');
            $table->boolean('utiliza_sistemas_interoperaveis')->after('dpo_contato')->default(false);
            
            // Educação
            $table->integer('escolas_fundamental_inicial_municipal')->default(0)->after('utiliza_sistemas_interoperaveis');
            $table->integer('escolas_fundamental_final_municipal')->default(0)->after('escolas_fundamental_inicial_municipal');
            $table->integer('escolas_fundamental_final_estadual')->default(0)->after('escolas_fundamental_final_municipal');
            $table->integer('escolas_ensino_medio_estadual')->default(0)->after('escolas_fundamental_final_estadual');
            $table->boolean('realizou_hackathons_educacao')->after('escolas_ensino_medio_estadual')->default(false);
            $table->string('link_evidencia_hackathons_educacao')->nullable()->after('realizou_hackathons_educacao');
            
            // Universidades
            $table->boolean('tem_universidade_presencial')->after('link_evidencia_hackathons_educacao')->default(false);
            $table->text('universidades_lista')->nullable()->after('tem_universidade_presencial');
            
            // Letramentos e Capacitação
            $table->text('aplica_novos_letramentos')->nullable()->after('universidades_lista');
            
            // Compromisso
            $table->boolean('disposto_dedicar_tempo')->after('aplica_novos_letramentos')->default(false);
            $table->text('comite_participantes')->nullable()->after('disposto_dedicar_tempo');
            $table->string('carta_compromisso_path')->nullable()->after('comite_participantes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            $table->dropColumn([
                'responsavel_nome',
                'responsavel_cpf',
                'responsavel_telefone',
                'responsavel_email',
                'responsavel_orgao',
                'responsavel_funcao',
                'orgao_endereco',
                'prefeito_cpf',
                'prefeito_telefone',
                'possui_politica_governo_digital',
                'link_evidencia_governo_digital',
                'realizou_cpsi',
                'link_evidencia_cpsi',
                'possui_masterplan',
                'link_evidencia_masterplan',
                'possui_plano_diretor_smart',
                'link_evidencia_plano_diretor',
                'secretaria_inovacao',
                'secretaria_tecnologia_smart',
                'secretaria_engenharia',
                'conhece_comunidade_inteligente',
                'usa_ferramenta_gestao_identidades',
                'possui_normativa_lgpd',
                'link_evidencia_lgpd',
                'dpo_nome',
                'dpo_contato',
                'utiliza_sistemas_interoperaveis',
                'escolas_fundamental_inicial_municipal',
                'escolas_fundamental_final_municipal',
                'escolas_fundamental_final_estadual',
                'escolas_ensino_medio_estadual',
                'realizou_hackathons_educacao',
                'link_evidencia_hackathons_educacao',
                'tem_universidade_presencial',
                'universidades_lista',
                'aplica_novos_letramentos',
                'disposto_dedicar_tempo',
                'comite_participantes',
                'carta_compromisso_path'
            ]);
        });
    }
};
