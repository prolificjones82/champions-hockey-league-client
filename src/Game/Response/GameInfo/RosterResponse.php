<?php

namespace ChampionsLeagueClient\Game\Response\GameInfo;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class RosterResponse implements ReadonlyInterface
{
    public function __construct(
        /**
         * @var array<int, RosterPlayerResponse>
         */
        public array $home,
        /**
         * @var array<int, RosterPlayerResponse>
         */
        public array $visitor,
    ) {}

    public function toArray(): array
    {
        return [
            'home' => array_map(
                static fn(RosterPlayerResponse $player): array => $player->toArray(),
                $this->home,
            ),
            'visitor' => array_map(
                static fn(RosterPlayerResponse $player): array => $player->toArray(),
                $this->visitor,
            ),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            home: array_map(
                RosterPlayerResponse::fromArray(...),
                $data['home'] ?? [],
            ),
            visitor: array_map(
                RosterPlayerResponse::fromArray(...),
                $data['visitor'] ?? [],
            ),
        );
    }
}
