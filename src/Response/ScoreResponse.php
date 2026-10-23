<?php

namespace ChampionsLeagueClient\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class ScoreResponse implements ReadonlyInterface
{
    public function __construct(
        public ScoreTeamResponse $final,
        public ScoreTeamResponse $first_period,
        public ScoreTeamResponse $second_period,
        public ScoreTeamResponse $third_period,
        public ?ScoreTeamResponse $overtime,
        public ?ScoreTeamResponse $shootout,
    ) {}

    public function toArray(): array
    {
        return [
            'final' => $this->final->toArray(),
            'first_period' => $this->first_period->toArray(),
            'second_period' => $this->second_period->toArray(),
            'third_period' => $this->third_period->toArray(),
            'overtime' => ($this->overtime) ? $this->overtime->toArray() : null,
            'shootout' => ($this->shootout) ? $this->shootout->toArray() : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            final: ScoreTeamResponse::fromArray($data['final']),
            first_period: ScoreTeamResponse::fromArray($data['first_period']),
            second_period: ScoreTeamResponse::fromArray($data['second_period']),
            third_period: ScoreTeamResponse::fromArray($data['third_period']),
            overtime: (isset($data['overtime'])) ? ScoreTeamResponse::fromArray($data['overtime']) : null,
            shootout: (isset($data['shootout'])) ? ScoreTeamResponse::fromArray($data['shootout']) : null,
        );
    }
}
