<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class PlayerStatPointData implements ReadonlyInterface
{
    public function __construct(
        public int $home,
        public int $away,
    ) {}

    public function toArray(): array
    {
        return [
            'home' => $this->home,
            'away' => $this->away,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            home: $data['home'] ?? 0,
            away: $data['away'] ?? 0,
        );
    }
}
