<?php
namespace App\Resources\LinkPagamento;

use App\Resources\Empresa\EmpresaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkPagamentoUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'value' => $this->value,
            'url' => $this->url,
            'active' => $this->isActive(),
            'chargeType' => $this->getChargeType(),
            'billingType' => $this->getBillingType(),
            'subscriptionCycle' => $this->getSubscriptionCycle(),
            'description' => $this->description,
            'endDate' => $this->endDate ? $this->endDate->format('d-m-Y') : null,
            'deleted' => $this->deleted,
            'viewCount' => $this->viewCount,
            'maxInstallmentCount' => $this->maxInstallmentCount,
            'dueDateLimitDays' => $this->dueDateLimitDays,
            'notificationEnabled' => $this->notificationEnabled,
            'isAddressRequired' => $this->isAddressRequired,
            'externalReference' => $this->externalReference,
            'criado_em' => $this->criado_em,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at->toDateString(),
            'updated_at' => $this->updated_at->toDateString(),
        ];

        // Se o parâmetro 'include_empresa' for verdadeiro, incluir a empresa
        if ($request->has('include_empresa') && $request->input('include_empresa') === 'true') {
            $data['empresa'] = new EmpresaResource($this->empresa);
        }

        return $data;
    }

    /**
     * Retorna o 'chargeType' em português
     */
    private function getChargeType(): string
    {
        $chargeTypes = [
            'DETACHED' => 'Desvinculado',
            'RECURRENT' => 'Recorrente',
            'INSTALLMENT' => 'Parcelado'
        ];

        return $chargeTypes[$this->chargeType] ?? $this->chargeType;
    }

    /**
     * Retorna o 'billingType' em português
     */
    private function getBillingType(): string
    {
        $billingTypes = [
            'UNDEFINED' => 'Indefinido',
            'BOLETO' => 'Boleto',
            'CREDIT_CARD' => 'Cartão de Crédito',
            'PIX' => 'PIX'
        ];

        return $billingTypes[$this->billingType] ?? $this->billingType;
    }

    /**
     * Retorna o 'subscriptionCycle' em português
     */
    private function getSubscriptionCycle(): string
    {
        $subscriptionCycles = [
            '' => 'Não especificado',
            'WEEKLY' => 'Semanal',
            'BIWEEKLY' => 'Quinzenal',
            'MONTHLY' => 'Mensal',
            'BIMONTHLY' => 'Bimestral',
            'QUARTERLY' => 'Trimestral',
            'SEMIANNUALLY' => 'Semestral',
            'YEARLY' => 'Anual'
        ];

        return $subscriptionCycles[$this->subscriptionCycle] ?? $this->subscriptionCycle;
    }



    /**
     * Retorna o 'chargeType' em português
     */
    private function isActive(): string
    {
        return $this->active ? 'Ativo' : 'Inativo';
    }
}
