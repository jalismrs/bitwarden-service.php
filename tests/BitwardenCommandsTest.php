<?php

namespace Jalismrs\Bitwarden\Tests;

use Jalismrs\Bitwarden\BitwardenCommands;
use PHPUnit\Framework\TestCase;

class BitwardenCommandsTest extends TestCase
{
    public function testSearchCommandIsBuiltWithOrganizationId(): void
    {
        self::assertSame(
            ['bw', 'list', 'items', '--organizationid', 'org-123', '--search', 'example', '--session', 'session-token'],
            BitwardenCommands::SEARCH_ITEMS_COMMAND('session-token', 'org-123', 'example'),
        );
    }

    public function testSearchCommandIsBuiltWithoutEmptyOrganizationId(): void
    {
        self::assertSame(
            ['bw', 'list', 'items', '--search', 'example', '--session', 'session-token'],
            BitwardenCommands::SEARCH_ITEMS_COMMAND('session-token', null, 'example'),
        );
    }
}
