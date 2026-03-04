<?php

namespace Jalismrs\Bitwarden\Tests\Dummies;

use Jalismrs\Bitwarden\BitwardenServiceDelegate;

class DummyBitwardenServiceDelegate implements BitwardenServiceDelegate
{
    public ?string $storedSession = null;

    public function __construct(
        private ?string $organizationId = null,
        private string $userEmail = 'john@example.test',
        private string $userPassword = 'secret-password',
        private ?string $restoredSession = null,
    ) {}

    public function getOrganizationId(): ?string
    {
        return $this->organizationId;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getUserPassword(): string
    {
        return $this->userPassword;
    }

    public function storeSession(string $session): void
    {
        $this->storedSession = $session;
    }

    public function restoreSession(): ?string
    {
        return $this->restoredSession;
    }
}
