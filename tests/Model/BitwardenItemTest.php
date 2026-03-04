<?php

namespace Jalismrs\Bitwarden\Tests\Model;

use Jalismrs\Bitwarden\Model\BitwardenItem;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class BitwardenItemTest extends TestCase
{
    public function testItemsAreParsedFromSequentialJsonArray(): void
    {
        $items = BitwardenItem::arrayFromJson(json_encode([
            [
                'object' => 'item',
                'id' => 'item-1',
                'organizationId' => 'org-123',
                'folderId' => null,
                'type' => 1,
                'name' => 'Example Item',
                'collectionIds' => ['collection-1'],
                'revisionDate' => '2026-03-04T10:00:00+00:00',
            ],
        ], JSON_THROW_ON_ERROR));

        self::assertCount(1, $items);
        self::assertSame('item-1', $items[0]->getId());
    }

    public function testNonSequentialJsonArrayIsRejected(): void
    {
        $this->expectException(RuntimeException::class);

        BitwardenItem::arrayFromJson(json_encode([
            'item-1' => [
                'object' => 'item',
                'id' => 'item-1',
                'type' => 1,
                'name' => 'Example Item',
            ],
        ], JSON_THROW_ON_ERROR));
    }
}
