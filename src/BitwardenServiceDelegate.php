<?php

namespace Jalismrs\Bitwarden;

interface BitwardenServiceDelegate
{
    public function getOrganizationId(): string;

    public function getUserEmail(): string;

    public function getUserPassword(): string;

    public function storeSession(string $session): void;

    public function restoreSession(): ?string;
}