<?php

namespace App\Models\Empresa;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpresaLinksPagamento extends Model
{
    use SoftDeletes;

    protected $table = 'empresa_links_pagamento';

    protected $fillable = [

        /** Qual empresa pertence a esse link */
        'empresa_id',

        /** usuário que criou o link */
        'criado_por',

        /** id do link no assas */
        'id_link_assas',

        /** Nome do link de pagamentos */
        'name',

        /** Valor do link de pagamentos, caso não informado o pagador poderá informar o quanto deseja pagar */
        'value',

        /** Se o link está ativo */
        'active',

        /**
         * Forma de cobrança
         * DETACHED, RECURRENT , INSTALLMENT
         */
        'chargeType',

        /** Essa url é a url de link de pgamento gerado lá no assas */
        'url',

        /**
         * Forma de pagamento permitida
         * UNDEFINED , BOLETO , CREDIT_CARD, PIX
         */
        'billingType',

        /** Periodicidade da cobrança, caso o chargeType seja RECURRENT
         * "", WEEKLY , BIWEEKLY, MONTHLY, BIMONTHLY, QUARTERLY, SEMIANNUALLY, YEARLY
         */
        'subscriptionCycle',

        /** Descrição do link de pagamentos */
        'description',

        /** Data de encerramento */
        'endDate',

        /** Para SoftDeletes */
        'deleted',

        /** NÃO SEI AINDA */
        'viewCount',

        /**
         *Quantidade máxima de parcelas que seu cliente poderá parcelar o valor do link de pagamentos caso a forma de cobrança selecionado seja Parcelamento. Caso não informado o valor padrão será de 1 parcela
         */
        'maxInstallmentCount',

        /**
         * Quantidade de dias úteis que o seu cliente poderá pagar após o boleto ser gerado (Para forma de pagamento como Boleto)
          */
        'dueDateLimitDays',

        /**
         * Define se os clientes cadastrados pelo link de pagamentos terão as notificações habilitadas. Caso não seja informado o valor padrão será true
         */
        'notificationEnabled',

        /**
         * True para tornar obrigatório o preenchimento de dados de endereço no checkout.
         */
        'isAddressRequired',

        /** Um id ou qual quer informação que você precise futuramente */
        'externalReference',

        /**
         * Para SoftDeletes
         */
        'deleted_at',

        /**
         * Registro de quando o link foi criado
         */
        'criado_em'
    ];

    // Definindo os tipos de dados para as colunas
    protected $casts = [
        'criado_em' => 'datetime',
        'endDate' => 'datetime',
        'deleted_at' => 'datetime', // Para SoftDeletes
    ];

    // Relacionamento com a empresa
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'empresa_id');
    }

    // Relacionamento com o usuário que criou
    public function criadoPor()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }
}
