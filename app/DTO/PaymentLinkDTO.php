<?php

namespace App\DTO;

readonly class PaymentLinkDTO
{
    public function __construct(
        public string $billingType,
        public string $chargeType,
        public string $name,
        public string $description,
        public string $endDate,
        public float $value,
        public int $dueDateLimitDays,
        public string $externalReference,
        public bool $notificationEnabled,
        public array $callback,
        public bool $isAddressRequired,
        public ?int $maxInstallmentCount,
        public ?string $cycle,
        public ?int $empresa_id
    ) {}
}
