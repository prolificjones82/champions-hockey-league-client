<?php

namespace ChampionsLeagueClient\Game\Response\GameInfo;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class RosterPlayerResponse implements ReadonlyInterface
{
    public function __construct(
        public int $playerId,
        public int $matchPlayerId,
        public string $name,
        public string $surname,
        public int $jersey,
        public string $position,
        public string $roster,
    ) {}

    public function toArray(): array
    {
        return [
            'playerId' => $this->playerId,
            'matchPlayerId' => $this->matchPlayerId,
            'name' => $this->name,
            'surname' => $this->surname,
            'jersey' => $this->jersey,
            'position' => $this->position,
            'roster' => $this->roster,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['playerId'],
            matchPlayerId: $data['matchPlayerId'],
            name: $data['name'],
            surname: $data['surname'],
            jersey: $data['jersey'],
            position: $data['position'],
            roster: $data['roster'],
        );
    }
}
