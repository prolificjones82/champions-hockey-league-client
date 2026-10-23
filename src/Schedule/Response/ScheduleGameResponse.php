<?php

namespace ChampionsLeagueClient\Schedule\Response;

use DateTime;
use ChampionsLeagueClient\Constants;
use ChampionsLeagueClient\Response\TeamResponse;
use ChampionsLeagueClient\Schedule\Enums\League;
use ChampionsLeagueClient\Response\ResultResponse;
use ChampionsLeagueClient\Schedule\Enums\GameStatus;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class ScheduleGameResponse implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public DateTime $start_date,
        public League $league_id,
        public string $league,
        public int $season,
        public ?string $round,
        public ?string $group,
        public TeamResponse $home,
        public TeamResponse $guest,
        public GameStatus $status,
        public ?int $overtimeCount,
        public ResultResponse $results,
        public string $actual_time_name,
        public string $actual_time_alias,
        public int $last_event_time,
        public string $arena,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date->format(Constants::DATETIME_FORMAT),
            'league_id' => $this->league_id->value,
            'league' => $this->league,
            'season' => $this->season,
            'round' => $this->round,
            'group' => $this->group,
            'home' => $this->home->toArray(),
            'guest' => $this->guest->toArray(),
            'status' => $this->status->value,
            'overtimeCount' => $this->overtimeCount ?? null,
            'results' => $this->results->toArray(),
            'actual_time_name' => $this->actual_time_name,
            'actual_time_alias' => $this->actual_time_alias,
            'last_event_time' => $this->last_event_time,
            'arena' => $this->arena,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            start_date: new DateTime($data['start_date']),
            league_id: League::tryFrom($data['league_id']),
            league: $data['league'],
            season: $data['season'],
            round: $data['round'],
            group: $data['group'] ?? null,
            home: TeamResponse::fromArray($data['home']),
            guest: TeamResponse::fromArray($data['guest']),
            status: isset($data['status'])
                ? GameStatus::fromApiValue($data['status'])
                : GameStatus::BEFORE_MATCH,
            overtimeCount: $data['overtimeCount'] ?? null,
            results: ResultResponse::fromArray($data['results']),
            actual_time_name: $data['actual_time_name'],
            actual_time_alias: $data['actual_time_alias'],
            last_event_time: $data['last_event_time'],
            arena: $data['arena'],
        );
    }
}
