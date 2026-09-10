<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('solicitudes_compra', function (Blueprint $table) {
        $table->string('archivo')->nullable()->after('justificacion');
    });
}

public function down(): void
{
    Schema::table('solicitudes_compra', function (Blueprint $table) {
        $table->dropColumn('archivo');
    });
}
};
