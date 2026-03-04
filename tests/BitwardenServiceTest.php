<?php

namespace Jalismrs\Bitwarden\Tests;

use Jalismrs\Bitwarden\BitwardenService;
use Jalismrs\Bitwarden\Tests\Dummies\DummyBitwardenServiceDelegate;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BitwardenServiceTest extends TestCase
{
    private string $originalPath;

    private string $logFile;

    private string $runtimeDir;

    private string $scenarioFile;

    protected function setUp(): void
    {
        parent::setUp();

        $fixturesBin = __DIR__.'/Fixtures/bin';
        $this->originalPath = getenv('PATH') ?: '';
        $this->runtimeDir = __DIR__.'/Fixtures/runtime';
        $this->logFile = $this->runtimeDir.'/log.jsonl';
        $this->scenarioFile = $this->runtimeDir.'/scenario.txt';

        if (!is_dir($this->runtimeDir)) {
            mkdir($this->runtimeDir, 0777, true);
        }

        @unlink($this->logFile);
        @unlink($this->scenarioFile);
        putenv('PATH='.$fixturesBin.PATH_SEPARATOR.$this->originalPath);
    }

    protected function tearDown(): void
    {
        putenv('PATH='.$this->originalPath);

        @unlink($this->logFile);
        @unlink($this->scenarioFile);

        parent::tearDown();
    }

    public function testItemsAreReturnedWhenStoredSessionIsUnlocked(): void
    {
        $this->writeScenario('unlocked');

        $delegate = new DummyBitwardenServiceDelegate('org-123', restoredSession: 'existing-session-token');
        $service = new BitwardenService($delegate);

        $items = $service->searchItems('example');

        self::assertCount(1, $items);
        self::assertSame('Example Item', $items[0]->getName());
        self::assertNull($delegate->storedSession);
        self::assertSame(
            [
                ['status', '--session', 'existing-session-token'],
                ['list', 'items', '--organizationid', 'org-123', '--search', 'example', '--session', 'existing-session-token'],
            ],
            $this->readLoggedCommands(),
        );
    }

    public function testSessionIsUnlockedWhenStatusIsLocked(): void
    {
        $this->writeScenario('locked');

        $delegate = new DummyBitwardenServiceDelegate('org-123', restoredSession: 'stale-session-token');
        $service = new BitwardenService($delegate);

        $items = $service->searchItems('example');

        self::assertCount(1, $items);
        self::assertSame('unlocked-session-token', $delegate->storedSession);
        self::assertSame(
            [
                ['status', '--session', 'stale-session-token'],
                ['unlock', '--raw', 'secret-password'],
                ['list', 'items', '--organizationid', 'org-123', '--search', 'example', '--session', 'unlocked-session-token'],
            ],
            $this->readLoggedCommands(),
        );
    }

    public function testSessionIsCreatedByLoginWhenStatusIsUnauthenticated(): void
    {
        $this->writeScenario('unauthenticated');

        $delegate = new DummyBitwardenServiceDelegate('org-123');
        $service = new BitwardenService($delegate);

        $items = $service->searchItems('example');

        self::assertCount(1, $items);
        self::assertSame('logged-session-token', $delegate->storedSession);
        self::assertSame(
            [
                ['status'],
                ['login', 'john@example.test', 'secret-password', '--raw'],
                ['list', 'items', '--organizationid', 'org-123', '--search', 'example', '--session', 'logged-session-token'],
            ],
            $this->readLoggedCommands(),
        );
    }

    public function testProcessFailureIsPropagatedWhenSearchFails(): void
    {
        $this->writeScenario('search-failure');

        $delegate = new DummyBitwardenServiceDelegate('org-123', restoredSession: 'existing-session-token');
        $service = new BitwardenService($delegate);

        $this->expectException(ProcessFailedException::class);

        $service->searchItems('example');
    }

    private function writeScenario(string $scenario): void
    {
        file_put_contents($this->scenarioFile, $scenario);
    }

    private function readLoggedCommands(): array
    {
        $lines = file($this->logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        return array_map(
            static fn(string $line): array => json_decode($line, true, 512, JSON_THROW_ON_ERROR),
            $lines ?: [],
        );
    }
}
