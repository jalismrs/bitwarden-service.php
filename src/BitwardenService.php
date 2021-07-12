<?php

namespace Jalismrs\Bitwarden;

use Jalismrs\Bitwarden\Model\BitwardenItem;
use Jalismrs\Bitwarden\Model\BitwardenStatus;
use JsonException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BitwardenService
{
    public function __construct(
        private BitwardenServiceDelegate $delegate,
    ) {}

    /**
     * @throws JsonException
     * @throws ProcessFailedException
     */
    public function getStatus(?string $session): BitwardenStatus
    {
        $output = $this->execCommand(BitwardenCommands::STATUS_COMMAND($session));
        return BitwardenStatus::fromJson($output);
    }

    /**
     * @throws JsonException
     */
    public function getSession(): string
    {
        $session = $this->delegate->restoreSession();
        $status = $this->getStatus($session);

        if ($session !== null && $status->getStatus() === BitwardenStatus::STATUS_UNLOCKED) {
            return $session;
        }

        else if ($status->getStatus() === BitwardenStatus::STATUS_LOCKED) {
            $session = $this->execCommand(BitwardenCommands::UNLOCK_COMMAND(
                $this->delegate->getUserPassword(),
            ));
        }

        else if ($status->getStatus() === BitwardenStatus::STATUS_UNAUTHENTICATED) {
            $session = $this->execCommand(BitwardenCommands::LOGIN_COMMAND(
                $this->delegate->getUserEmail(),
                $this->delegate->getUserPassword(),
            ));
        }

        $this->delegate->storeSession($session);

        return $session;
    }

    /**
     * @throws JsonException
     */
    public function searchItems(string $search): array
    {
        $session = $this->getSession();
        $output = $this->execCommand(BitwardenCommands::SEARCH_ITEMS_COMMAND(
            $session,
            $this->delegate->getOrganizationId(),
            $search,
        ));

        return BitwardenItem::arrayFromJson($output);
    }

    /**
     * @throws ProcessFailedException
     */
    private function execCommand(array $command): string
    {
        $process = new Process($command, null, [
            'NODE_EXTRA_CA_CERTS' => '/Users/sbntt/Downloads/Certificat_NAS/syno-ca-cert.pem'
        ]);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}