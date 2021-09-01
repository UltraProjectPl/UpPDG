<?php

declare(strict_types=1);

namespace App\Employer\Domain;

use App\SharedKernel\Domain\TimeStamp;
use App\User\Domain\User;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;

class Offer implements JsonSerializable
{
    use TimeStamp;

    private UuidInterface $id;

    public function __construct(
        private User $creator,
        private string $title,
        private string $companyName,
        private PaymentSpreads $paymentSpreads,
        private string $city,
        private bool $remoteWorkPossible = false,
        private bool $remoteWorkOnly = false,
        private bool $active = true,
        private ?string $nip = null,
        private ?string $tin = null,
    ) {
        $this->id = Uuid::uuid4();

        $this->setTimeStamp();
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

    public function getCity(): string
    {
        return $this->city;
    }

    public function isActive(): bool
    {
        return $this->active;
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

    public function activate(): self
    {
        if (true === $this->active) {
            throw new RuntimeException("Offer '{$this->id->toString()}' is already active.");
        }

        $this->active = true;

        return $this;
    }

    public function deactivate(): self
    {
        if (false === $this->active) {
            throw new RuntimeException("Offer '{$this->id->toString()}' is already deactivate.");
        }

        $this->active = false;

        return $this;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'creator' => $this->creator,
            'title' => $this->title,
            'companyName' => $this->companyName,
            'paymentSpreads' => $this->paymentSpreads,
            'city' => $this->city,
            'remoteWorkPossible' => $this->remoteWorkPossible,
            'remoteWorkOnly' => $this->remoteWorkOnly,
            'active' => $this->active,
            'nip' => $this->nip,
            'tin' => $this->tin,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
            'deletedAt' => $this->deletedAt,
        ];
    }
}
