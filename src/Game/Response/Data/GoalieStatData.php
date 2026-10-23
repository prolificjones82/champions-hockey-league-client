<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use DateTime;
use ChampionsLeagueClient\Constants;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class GoalieStatData implements ReadonlyInterface
{
    public function __construct(
        public int $playerId,
        public string $name,
        public string $surname,
        public DateTime $birthdate,
        public int $teamId,
        public string $teamName,
        public string $teamShortcut,
        public int $jersey,
        public int $matches,
        public int $wonMatches,
        public int $lostMatches,
        public int $saves,
        public int $goalsAgainst,
        public int $savesPercent,
        public int $average,
        public int $zeros,
        public int $timeOnIce,
        public int $secondsPerGame,
        public int $minutesInPlay,
        public bool $visibleGames,
        public bool $visibleShutouts,
        public bool $visibleWins,
        public int $starsGames,
        public int $starsShutouts,
        public int $starsWins,
        public int $milestoneGames,
        public int $milestoneShutouts,
        public int $milestoneWins,
    ) {}

    public function toArray(): array
    {
        return [
            'playerId' => $this->playerId,
            'name' => $this->name,
            'surname' => $this->surname,
            'birthdate' => $this->birthdate->format(Constants::DATE_FORMAT),
            'teamId' => $this->teamId,
            'teamName' => $this->teamName,
            'teamShortcut' => $this->teamShortcut,
            'jersey' => $this->jersey,
            'matches' => $this->matches,
            'wonMatches' => $this->wonMatches,
            'lostMatches' => $this->lostMatches,
            'saves' => $this->saves,
            'goalsAgainst' => $this->goalsAgainst,
            'savesPercent' => $this->savesPercent,
            'average' => $this->average,
            'zeros' => $this->zeros,
            'timeOnIce' => $this->timeOnIce,
            'secondsPerGame' => $this->secondsPerGame,
            'minutesInPlay' => $this->minutesInPlay,
            'visibleGames' => $this->visibleGames,
            'visibleShutouts' => $this->visibleShutouts,
            'visibleWins' => $this->visibleWins,
            'starsGames' => $this->starsGames,
            'starsShutouts' => $this->starsShutouts,
            'starsWins' => $this->starsWins,
            'milestoneGames' => $this->milestoneGames,
            'milestoneShutouts' => $this->milestoneShutouts,
            'milestoneWins' => $this->milestoneWins,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            playerId: $data['playerId'],
            name: $data['name'],
            surname: $data['surname'],
            birthdate: new DateTime($data['birthdate']),
            teamId: $data['teamId'],
            teamName: $data['teamName'],
            teamShortcut: $data['teamShortcut'],
            jersey: $data['jersey'],
            matches: $data['matches'],
            wonMatches: $data['wonMatches'],
            lostMatches: $data['lostMatches'],
            saves: $data['saves'],
            goalsAgainst: $data['goalsAgainst'],
            savesPercent: $data['savesPercent'],
            average: $data['average'],
            zeros: $data['zeros'],
            timeOnIce: $data['timeOnIce'],
            secondsPerGame: $data['secondsPerGame'],
            minutesInPlay: $data['minutesInPlay'],
            visibleGames: $data['visibleGames'],
            visibleShutouts: $data['visibleShutouts'],
            visibleWins: $data['visibleWins'],
            starsGames: $data['starsGames'],
            starsShutouts: $data['starsShutouts'],
            starsWins: $data['starsWins'],
            milestoneGames: $data['milestoneGames'],
            milestoneShutouts: $data['milestoneShutouts'],
            milestoneWins: $data['milestoneWins'],
        );
    }
}
