<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_email')->unique();
            $table->string('company_phone');
            $table->string('cnpj')->unique();
            $table->string('social_reason');
            $table->timestamps();
            $table->softDeletes();
            $table->uuid('chave')->unique();
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresas');
    }
}
