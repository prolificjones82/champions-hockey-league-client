<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use DateTime;
use ChampionsLeagueClient\Constants;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterPlayerResponse;

final readonly class GoalData implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public string $team,
        public string $currentScore,
        public DateTime $realTime,
        public int $scoreboardTime,
        public string $balance,
        public bool $ea,
        public bool $en,
        public bool $shooting,
        public bool $technical,
        public RosterPlayerResponse $scorer,
        /**
         * @var array<int, RosterPlayerResponse>
         */
        public array $assistants,
        public GoalAttendantData $attendants,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'team' => $this->team,
            'currentScore' => $this->currentScore,
            'realTime' => $this->realTime->format(Constants::DATETIME_FORMAT),
            'scoreboardTime' => $this->scoreboardTime,
            'balance' => $this->balance,
            'ea' => $this->ea,
            'en' => $this->en,
            'shooting' => $this->shooting,
            'technical' => $this->technical,
            'scorer' => $this->scorer->toArray(),
            'assistants' => array_map(
                static fn(RosterPlayerResponse $player) => $player->toArray(),
                $this->assistants,
            ),
            'attendants' => $this->attendants->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            team: $data['team'],
            currentScore: $data['currentScore'],
            realTime: new DateTime($data['realTime']),
            scoreboardTime: $data['scoreboardTime'],
            balance: $data['balance'],
            ea: $data['ea'],
            en: $data['en'],
            shooting: $data['shooting'],
            technical: $data['technical'],
            scorer: RosterPlayerResponse::fromArray($data['scorer']),
            assistants: array_map(
                RosterPlayerResponse::fromArray(...),
                $data['assistants'] ?? [],
            ),
            attendants: GoalAttendantData::fromArray($data['attendants']),
        );
    }
}
