<?php

namespace App\Resources\Empresa;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource  extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request) : array
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'company_email' => $this->company_email,
            'company_phone' => $this->company_phone,
            'cnpj' => $this->cnpj,
            'social_reason' => $this->social_reason,
            'chave' => $this->chave,
        ];
    }
}
