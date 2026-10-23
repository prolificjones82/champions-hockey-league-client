<?php

namespace ChampionsLeagueClient\Schedule\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class ScheduleResponse implements ReadonlyInterface
{
    public function __construct(
        /**
         * @var array<int, ScheduleGameResponse>
         */
        public array $matches,
    ) {}

    public function toArray(): array
    {
        return [
            'matches' => array_map(
                static fn(ScheduleGameResponse $match): array => $match->toArray(),
                $this->matches,
            ),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            matches: array_map(
                ScheduleGameResponse::fromArray(...),
                $data['matches'] ?? [],
            ),
        );
    }
}
