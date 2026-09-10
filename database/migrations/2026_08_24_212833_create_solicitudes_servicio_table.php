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
    Schema::create('solicitudes_servicio', function (Blueprint $table) {
        $table->id();
        $table->string('folio')->unique(); // Ej: SER-00001
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('email_notificacion');
        $table->string('tipo_servicio'); // Ej: Mantenimiento, Redes, Soporte
        $table->text('descripcion_problema');
	$table->string('estado')->default('Pendiente');
	$table->timestamps();
    });

    Schema::create('servicio_archivos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('solicitud_servicio_id')->constrained('solicitudes_servicio')->onDelete('cascade');
        $table->string('nombre_original');
        $table->string('ruta_archivo');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_servicio');
    }
};
