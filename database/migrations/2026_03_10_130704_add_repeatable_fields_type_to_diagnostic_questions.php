<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            // Remove a constraint antiga
            DB::statement('ALTER TABLE diagnostic_questions DROP CONSTRAINT IF EXISTS diagnostic_questions_type_check');
            
            // Adiciona a nova constraint com o tipo 'repeatable_fields'
            DB::statement("ALTER TABLE diagnostic_questions ADD CONSTRAINT diagnostic_questions_type_check CHECK (type IN ('yes_no', 'yes_no_evidence', 'checkbox', 'multiple_input', 'repeatable_fields', 'text'))");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() !== 'sqlite') {
            // Remove a constraint com o novo tipo
            DB::statement('ALTER TABLE diagnostic_questions DROP CONSTRAINT IF EXISTS diagnostic_questions_type_check');
            
            // Restaura a constraint antiga sem 'repeatable_fields'
            DB::statement("ALTER TABLE diagnostic_questions ADD CONSTRAINT diagnostic_questions_type_check CHECK (type IN ('yes_no', 'yes_no_evidence', 'checkbox', 'multiple_input', 'text'))");
        }
    }
};
