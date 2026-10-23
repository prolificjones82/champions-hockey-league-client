<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterPlayerResponse;

final readonly class GoalkeeperChangeData implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public string $team,
        public int $time,
        public RosterPlayerResponse $player,
        public ?RosterPlayerResponse $outgoingGoalkeeper,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'team' => $this->team,
            'time' => $this->time,
            'player' => $this->player->toArray(),
            'outgoingGoalkeeper' => ($this->outgoingGoalkeeper) ? $this->outgoingGoalkeeper->toArray() : null,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            team: $data['team'],
            time: $data['time'],
            player: RosterPlayerResponse::fromArray($data['player']),
            outgoingGoalkeeper: isset($data['outgoingGoalkeeper']) ? RosterPlayerResponse::fromArray($data['outgoingGoalkeeper']) : null,
        );
    }
}
