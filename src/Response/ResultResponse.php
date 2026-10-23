<?php

namespace ChampionsLeagueClient\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class ResultResponse implements ReadonlyInterface
{
    public function __construct(
        public bool $extra_time,
        public bool $shooting,
        public bool $contumacy,
        public ScoreResponse $score,
    ) {}

    public function toArray(): array
    {
        return [
            'extra_time' => $this->extra_time,
            'shooting' => $this->shooting,
            'contumacy' => $this->contumacy,
            'score' => $this->score->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            extra_time: $data['extra_time'],
            shooting: $data['shooting'],
            contumacy: $data['contumacy'],
            score: ScoreResponse::fromArray($data['score']),
        );
    }
}
