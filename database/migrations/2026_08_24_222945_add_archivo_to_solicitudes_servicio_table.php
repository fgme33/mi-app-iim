<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('solicitudes_servicio', function (Blueprint $table) {
        $table->string('archivo')->nullable()->after('descripcion_problema');
    });
}

public function down(): void
{
    Schema::table('solicitudes_servicio', function (Blueprint $table) {
        $table->dropColumn('archivo');
    });
}
};
