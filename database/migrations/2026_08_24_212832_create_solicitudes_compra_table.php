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
    Schema::create('solicitudes_compra', function (Blueprint $table) {
        $table->id();
        $table->string('folio')->unique(); // Ej: COM-00001
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('email_notificacion');
        $table->string('articulo_solicitado'); // Especifico de compras
        $table->text('justificacion');
	$table->string('estado')->default('Pendiente');
	$table->timestamps();
    });

    Schema::create('compra_archivos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('solicitud_compra_id')->constrained('solicitudes_compra')->onDelete('cascade');
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
        Schema::dropIfExists('solicitudes_compra');
    }
};
