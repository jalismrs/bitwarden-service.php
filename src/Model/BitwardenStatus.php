<?php

namespace Jalismrs\Bitwarden\Model;

use DateTime;
use Exception;
use JsonException;
use RuntimeException;

class BitwardenStatus
{
    public const STATUS_UNAUTHENTICATED = 'unauthenticated';
    public const STATUS_LOCKED = 'locked';
    public const STATUS_UNLOCKED = 'unlocked';

    private function __construct(
        private string $url,
        private ?DateTime $lastSync,
        private ?string $userEmail,
        private ?string $userId,
        private string $status,
    ) {}

    /**
     * @throws Exception
     */
    public static function fromArray(array $data): BitwardenStatus
    {
        return new BitwardenStatus(
            $data['serverUrl'] ?? throw new RuntimeException('Missing url in BitwardenStatus json string'),
            isset($data['lastSync']) ? new DateTime($data['lastSync']) : null,
            $data['userEmail'] ?? null,
            $data['userId'] ?? null,
            match($data['status']) {
                self::STATUS_UNAUTHENTICATED => self::STATUS_UNAUTHENTICATED,
                self::STATUS_LOCKED => self::STATUS_LOCKED,
                self::STATUS_UNLOCKED => self::STATUS_UNLOCKED,
                default => throw new RuntimeException('Invalid status BitwardenStatus json string'),
            },
        );
    }

    /**
     * @throws JsonException
     * @throws Exception
     */
    public static function fromJson(string $json): BitwardenStatus
    {
        $data = json_decode($json, true, $depth=512, JSON_THROW_ON_ERROR);
        return self::fromArray($data);
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getLastSync(): ?DateTime
    {
        return $this->lastSync;
    }

    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}