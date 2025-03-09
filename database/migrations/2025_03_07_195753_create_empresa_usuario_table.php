<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_empresa_usuario_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaUsuarioTable extends Migration
{
    public function up()
    {
        Schema::create('empresa_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamps();

            // Definir chaves estrangeiras
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade');

            // Definir restrição de unicidade para evitar duplicatas
            $table->unique(['empresa_id', 'usuario_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresa_usuario');
    }
}
