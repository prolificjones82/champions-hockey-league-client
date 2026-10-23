<?php

namespace ChampionsLeagueClient\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class ScoreTeamResponse implements ReadonlyInterface
{
    public function __construct(
        public int $score_home,
        public int $score_guest,
    ) {}

    public function toArray(): array
    {
        return [
            'score_home' => $this->score_home,
            'score_guest' => $this->score_guest,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            score_home: $data['score_home'],
            score_guest: $data['score_guest'],
        );
    }
}
