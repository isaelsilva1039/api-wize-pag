<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('empresa_links_pagamento', function (Blueprint $table) {
            $table->timestamp('criado_em')->nullable();
        });
    }

    public function down()
    {
        Schema::table('empresa_links_pagamento', function (Blueprint $table) {
            $table->dropColumn('criado_em');
        });
    }

};
