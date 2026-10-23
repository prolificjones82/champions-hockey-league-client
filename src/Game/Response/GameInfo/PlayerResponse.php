<?php

namespace ChampionsLeagueClient\Game\Response\GameInfo;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class PlayerResponse implements ReadonlyInterface
{
    public function __construct(
        public int $playerId,
        public int $matchPlayerId,
        public int $jersey,
        public string $name,
        public string $surname,
    ) {}

    public function toArray(): array
    {
        return [
            'playerId' => $this->playerId,
            'matchPlayerId' => $this->matchPlayerId,
            'jersey' => $this->jersey,
            'name' => $this->name,
            'surname' => $this->surname,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['playerId'],
            matchPlayerId: $data['matchPlayerId'],
            jersey: $data['jersey'],
            name: $data['name'],
            surname: $data['surname'],
        );
    }
}
