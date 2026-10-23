<?php

namespace ChampionsLeagueClient\Game\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\Data\GoalieStatData;

final readonly class GoalieStatResponse implements ReadonlyInterface
{
    public function __construct(
        /**
         * @var array<int, GoalieStatData>
         */
        public array $data,
    ) {}

    public function toArray(): array
    {
        return [
            'data' => array_map(
                static fn(GoalieStatData $goalie): array => $goalie->toArray(),
                $this->data,
            ),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            data: array_map(
                GoalieStatData::fromArray(...),
                $data['data'] ?? [],
            ),
        );
    }
}
