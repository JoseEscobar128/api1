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
        Schema::table('empleados', function (Blueprint $table) {
            $table->unsignedMediumInteger('puesto_id')->after('apellido_paterno');

            $table->foreign('puesto_id')
                  ->references('id')
                  ->on('puestos')
                  ->onDelete('cascade'); // Cambia a 'set null' si no quieres borrar empleados al borrar puesto
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['puesto_id']);
            $table->dropColumn('puesto_id');
        });
    }
};
