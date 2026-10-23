<?php

namespace ChampionsLeagueClient\Interfaces;

interface ReadonlyInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;

    /**
     * @param array<array-key, mixed> $data
     *
     * @return static
     */
    public static function fromArray(array $data);
}
