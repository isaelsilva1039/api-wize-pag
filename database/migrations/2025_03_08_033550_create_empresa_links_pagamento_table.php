<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresaLinksPagamentoTable extends Migration
{
    public function up()
    {
        Schema::create('empresa_links_pagamento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('criado_por');
            $table->string('id_link_assas');
            $table->string('name');
            $table->decimal('value', 10, 2);
            $table->boolean('active');
            $table->string('chargeType');
            $table->string('url');
            $table->string('billingType');
            $table->string('subscriptionCycle')->nullable();
            $table->text('description');
            $table->date('endDate');
            $table->boolean('deleted');
            $table->integer('viewCount');
            $table->integer('maxInstallmentCount');
            $table->integer('dueDateLimitDays');
            $table->boolean('notificationEnabled');
            $table->boolean('isAddressRequired');
            $table->string('externalReference');
            $table->softDeletes(); // Para SoftDeletes (campo deleted_at)
            $table->timestamps(); // Para created_at e updated_at
        });

        // Definir as chaves estrangeiras
        Schema::table('empresa_links_pagamento', function (Blueprint $table) {
            $table->foreign('empresa_id')->references('id')->on('empresas')->onDelete('cascade');
            $table->foreign('criado_por')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('empresa_links_pagamento');
    }
}
