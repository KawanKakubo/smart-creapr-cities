<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Garante que todas as submissões existentes sejam marcadas como ativas por padrão nesta implantação
        DB::table('submissions')->update(['is_active' => true]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Sem ação de reversão necessária
    }
};
