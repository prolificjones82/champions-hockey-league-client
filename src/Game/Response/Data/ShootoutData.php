<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterPlayerResponse;

final readonly class ShootoutData implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public string $team,
        public RosterPlayerResponse $scorer,
        public RosterPlayerResponse $goalkeeper,
        public int $order,
        public bool $scored,
        public string $currentScore,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'team' => $this->team,
            'scorer' => $this->scorer->toArray(),
            'goalkeeper' => $this->goalkeeper->toArray(),
            'order' => $this->order,
            'scored' => $this->scored,
            'currentScore' => $this->currentScore,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            team: $data['team'],
            scorer: RosterPlayerResponse::fromArray($data['scorer']),
            goalkeeper: RosterPlayerResponse::fromArray($data['goalkeeper']),
            order: $data['order'],
            scored: $data['scored'],
            currentScore: $data['currentScore'],
        );
    }
}
