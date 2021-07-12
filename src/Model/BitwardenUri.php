<?php

namespace Jalismrs\Bitwarden\Model;

use JsonException;

class BitwardenUri
{
    private function __construct(
        private ?string $match,
        private ?string $uri,
    ) {}

    public static function fromArray(array $data): BitwardenUri
    {
        return new BitwardenUri(
            $data['match'] ?? null,
            $data['uri'] ?? null,
        );
    }

    /**
     * @throws JsonException
     */
    public static function fromJson(string $json): BitwardenUri
    {
        $data = json_decode($json, true, $depth=512, JSON_THROW_ON_ERROR);
        return self::fromArray($data);
    }

    public function getMatch(): ?string
    {
        return $this->match;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }
}