<?php

namespace App\DTO\Empresa;

readonly class EmpresaDTO
{
    public function __construct(
        public string $companyName,
        public string $companyEmail,
        public string $companyPhone,
        public string $cnpj,
        public string $socialReason
    ) {}
}
