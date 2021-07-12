<?php

namespace Jalismrs\Bitwarden;

abstract class BitwardenCommands
{
    public static function STATUS_COMMAND(?string $session): array
    {
        return self::withNullableSession(['bw', 'status'], $session);
    }

    public static function LOGIN_COMMAND(string $username, string $password): array
    {
        return ['bw', 'login', $username, $password, '--raw'];
    }

    public static function UNLOCK_COMMAND(string $password): array
    {
        return ['bw', 'unlock', '--raw', $password];
    }

    public static function SEARCH_ITEMS_COMMAND(string $session, string $organizationid, string $search): array
    {
        return ['bw', 'list', 'items', '--organizationid', $organizationid, '--search', $search, '--session', $session];
    }

    private static function withNullableSession(array $cmd, ?string $session): array
    {
        if ($session !== null) {
            $cmd[] = '--session';
            $cmd[] = $session;
        }

        return $cmd;
    }
}