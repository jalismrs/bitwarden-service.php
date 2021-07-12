<?php

namespace Jalismrs\Bitwarden\Model;

use DateTime;
use Exception;
use JsonException;

class BitwardenLogin
{
    private function __construct(
        private ?string $username,
        private ?string $password,
        private ?string $topt,
        private ?DateTime $passwordRevisionDate,
    ) {}

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): BitwardenLogin
    {
        return new BitwardenLogin(
            $data['username'] ?? null,
            $data['password'] ?? null,
            $data['topt'] ?? null,
            isset($data['passwordRevisionDate']) ? new DateTime($data['passwordRevisionDate']) : null,
        );
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public static function fromJson(string $json): BitwardenLogin
    {
        $data = json_decode($json, true, $depth=512, JSON_THROW_ON_ERROR);
        return self::fromArray($data);
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getTopt(): ?string
    {
        return $this->topt;
    }

    public function getPasswordRevisionDate(): ?DateTime
    {
        return $this->passwordRevisionDate;
    }
}