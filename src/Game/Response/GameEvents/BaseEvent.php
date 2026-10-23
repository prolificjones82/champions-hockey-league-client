<?php

namespace ChampionsLeagueClient\Game\Response\GameEvents;

use ChampionsLeagueClient\Game\Enums\EventType;
use ChampionsLeagueClient\Game\Response\Data\GoalData;
use ChampionsLeagueClient\Game\Response\Data\PenaltyData;
use ChampionsLeagueClient\Game\Response\Data\ShootoutData;
use ChampionsLeagueClient\Game\Response\Data\GoalkeeperChangeData;

abstract readonly class BaseEvent
{
    public function __construct(
        public int $time,
        public EventType $type,
        public GoalkeeperChangeData|GoalData|PenaltyData|ShootoutData $data,
    ) {}
}
