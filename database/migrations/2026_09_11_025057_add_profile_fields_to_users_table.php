<?php
// database/migrations/2026_09_10_000000_add_profile_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_perfil')->nullable()->after('password');
            $table->string('telefono')->nullable();
            $table->string('orcid_id')->nullable();
            $table->string('scopus_id')->nullable();
            $table->text('doi')->nullable();
            $table->string('plaza')->nullable();
            $table->string('sni')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'foto_perfil', 'telefono', 'orcid_id',
                'scopus_id', 'doi', 'plaza', 'sni',
            ]);
        });
    }
};
