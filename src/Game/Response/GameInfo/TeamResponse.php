<?php

namespace ChampionsLeagueClient\Game\Response\GameInfo;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Response\TeamResponse as FullTeamResponse;

final readonly class TeamResponse implements ReadonlyInterface
{
    public function __construct(
        public FullTeamResponse $home,
        public FullTeamResponse $visitor,
    ) {}

    public function toArray(): array
    {
        return [
            'home' => $this->home->toArray(),
            'visitor' => $this->visitor->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            home: FullTeamResponse::fromArray($data['home']),
            visitor: FullTeamResponse::fromArray($data['visitor']),
        );
    }
}
