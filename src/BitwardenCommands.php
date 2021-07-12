<?php

namespace Jalismrs\Bitwarden;

abstract class BitwardenCommands
{
    public static function STATUS_COMMAND(?string $session): array
    {
        return self::withOptions(['bw', 'status'], [
            '--session' => $session,
        ]);
    }

    public static function LOGIN_COMMAND(string $username, string $password): array
    {
        return ['bw', 'login', $username, $password, '--raw'];
    }

    public static function UNLOCK_COMMAND(string $password): array
    {
        return ['bw', 'unlock', '--raw', $password];
    }

    public static function SEARCH_ITEMS_COMMAND(string $session, ?string $organizationId, string $search): array
    {
        return self::withOptions(['bw', 'list', 'items'], [
            '--organizationid' => $organizationId,
            '--search' => $search,
            '--session' => $session,
        ]);
    }

    private static function withOptions(array $cmd, array $options): array
    {
        foreach ($options as $name => $value) {
            if ($value !== null && !empty($value)) {
                $cmd[] = $name;
                $cmd[] = $value;
            }
        }

        return $cmd;
    }
}