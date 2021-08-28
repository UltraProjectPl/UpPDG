<?php
declare(strict_types=1);

namespace App\Employer\Domain;

use App\SharedKernel\Domain\TimeStamp;
use App\User\Domain\User;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Offer
{
    use TimeStamp;

    private UuidInterface $id;

    public function __construct(
        private User $creator,
        private string $title,
        private string $companyName,
        private PaymentSpreads $paymentSpreads,
        private bool $remoteWorkPossible = false,
        private bool $remoteWorkOnly = false,
        private ?string $nip = null,
        private ?string $tin = null,
    ) {
        $this->id = Uuid::uuid4();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreator(): User
    {
        return $this->creator;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getCompanyName(): string
    {
        return $this->companyName;
    }

    public function getPaymentSpreads(): PaymentSpreads
    {
        return $this->paymentSpreads;
    }

    public function isRemoteWorkPossible(): bool
    {
        return $this->remoteWorkPossible;
    }

    public function isRemoteWorkOnly(): bool
    {
        return $this->remoteWorkOnly;
    }

    public function getNip(): ?string
    {
        return $this->nip;
    }

    public function getTin(): ?string
    {
        return $this->tin;
    }
}