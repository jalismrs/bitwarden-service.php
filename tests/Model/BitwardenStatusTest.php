<?php

namespace Jalismrs\Bitwarden\Tests\Model;

use Jalismrs\Bitwarden\Model\BitwardenStatus;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BitwardenStatusTest extends TestCase
{
    public function testStatusIsParsedFromJson(): void
    {
        $status = BitwardenStatus::fromJson(json_encode([
            'serverUrl' => 'https://vault.bitwarden.test',
            'lastSync' => '2026-03-04T10:00:00+00:00',
            'userEmail' => 'john@example.test',
            'userId' => 'user-1',
            'status' => BitwardenStatus::STATUS_UNLOCKED,
        ], JSON_THROW_ON_ERROR));

        self::assertSame(BitwardenStatus::STATUS_UNLOCKED, $status->getStatus());
        self::assertSame('john@example.test', $status->getUserEmail());
    }

    public function testInvalidStatusIsRejectedFromJson(): void
    {
        $this->expectException(RuntimeException::class);

        BitwardenStatus::fromJson(json_encode([
            'serverUrl' => 'https://vault.bitwarden.test',
            'status' => 'invalid',
        ], JSON_THROW_ON_ERROR));
    }
}
