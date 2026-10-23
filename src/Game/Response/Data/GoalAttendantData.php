<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterPlayerResponse;

final readonly class GoalAttendantData implements ReadonlyInterface
{
    public function __construct(
        /**
         * @var array<int, RosterPlayerResponse>
         */
        public array $positive,
        /**
         * @var array<int, RosterPlayerResponse>
         */
        public array $negative,
    ) {}

    public function toArray(): array
    {
        return [
            'positive' => array_map(
                static fn(RosterPlayerResponse $player) => $player->toArray(),
                $this->positive,
            ),
            'negative' => array_map(
                static fn(RosterPlayerResponse $player) => $player->toArray(),
                $this->negative,
            ),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            positive: array_map(
                RosterPlayerResponse::fromArray(...),
                $data['positive'] ?? [],
            ),
            negative: array_map(
                RosterPlayerResponse::fromArray(...),
                $data['negative'] ?? [],
            ),
        );
    }
}
