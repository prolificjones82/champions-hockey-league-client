<?php

namespace ChampionsLeagueClient\Game\Response\GameInfo;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class TrainerResponse implements ReadonlyInterface
{
    public function __construct(
        public string $homeHeadCoach,
        public string $visitorHeadCoach,
    ) {}

    public function toArray(): array
    {
        return [
            'homeHeadCoach' => $this->homeHeadCoach,
            'visitorHeadCoach' => $this->visitorHeadCoach,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            homeHeadCoach: $data['homeHeadCoach'],
            visitorHeadCoach: $data['visitorHeadCoach'],
        );
    }
}
