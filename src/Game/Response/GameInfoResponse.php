<?php

namespace ChampionsLeagueClient\Game\Response;

use ChampionsLeagueClient\Response\ResultResponse;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameInfo\TeamResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\BilanceResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\RefereeResponse;
use ChampionsLeagueClient\Game\Response\GameInfo\TrainerResponse;

final readonly class GameInfoResponse implements ReadonlyInterface
{
    public function __construct(
        public string $actualTimeName,
        public string $actualTimeAlias,
        public string $stadium,
        public int $numberOfViewers,
        public RefereeResponse $referees,
        public TeamResponse $teamInfo,
        public TrainerResponse $trainers,
        public BilanceResponse $teamBilance,
        public ResultResponse $results,
        public RosterResponse $roster,
    ) {}

    public function toArray(): array
    {
        return [
            'actualTimeName' => $this->actualTimeName,
            'actualTimeAlias' => $this->actualTimeAlias,
            'stadium' => $this->stadium,
            'numberOfViewers' => $this->numberOfViewers,
            'referees' => $this->referees->toArray(),
            'teamInfo' => $this->teamInfo->toArray(),
            'trainers' => $this->trainers->toArray(),
            'teamBilance' => $this->teamBilance->toArray(),
            'results' => $this->results->toArray(),
            'roster' => $this->roster->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            actualTimeName: (isset($data['gameInfo'])) ? $data['gameInfo']['actualTimeName'] : $data['actualTimeName'],
            actualTimeAlias: (isset($data['gameInfo'])) ? $data['gameInfo']['actualTimeAlias'] : $data['actualTimeAlias'],
            stadium: (isset($data['gameInfo'])) ? $data['gameInfo']['stadium'] : $data['stadium'],
            numberOfViewers: (isset($data['gameInfo'])) ? $data['gameInfo']['numberOfViewers'] : $data['numberOfViewers'],
            referees: RefereeResponse::fromArray((isset($data['gameInfo'])) ? $data['gameInfo']['referees'] : $data['referees']),
            teamInfo: TeamResponse::fromArray((isset($data['gameInfo'])) ? $data['gameInfo']['teamInfo'] : $data['teamInfo']),
            trainers: TrainerResponse::fromArray((isset($data['gameInfo'])) ? $data['gameInfo']['trainers'] : $data['trainers']),
            teamBilance: BilanceResponse::fromArray((isset($data['gameInfo'])) ? $data['gameInfo']['teamBilance'] : $data['teamBilance']),
            results: ResultResponse::fromArray((isset($data['gameInfo'])) ? $data['gameInfo']['results'] : $data['results']),
            roster: RosterResponse::fromArray($data['roster']),
        );
    }
}
