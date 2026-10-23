<?php

namespace ChampionsLeagueClient\Standings\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class StandingsResponse implements ReadonlyInterface
{
    public function __construct(
        public ?int $groupId,
        public ?string $groupName,
        public bool $complete,
        /**
         * @var array<int, TeamResponse>
         */
        public array $clubs,
    ) {}

    public function toArray(): array
    {
        return [
            'groupId' => $this->groupId,
            'groupName' => $this->groupName,
            'complete' => (bool) $this->complete,
            'clubs' => array_map(
                static fn(TeamResponse $team): array => $team->toArray(),
                $this->clubs,
            ),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            groupId: isset($data['groupId']) ? $data['groupId'] : null,
            groupName: isset($data['groupName']) ? $data['groupName'] : null,
            complete: $data['complete'] ?? false,
            clubs: array_map(
                TeamResponse::fromArray(...),
                $data['clubs'] ?? [],
            ),
        );
    }
}
