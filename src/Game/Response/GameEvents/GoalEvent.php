<?php

namespace ChampionsLeagueClient\Game\Response\GameEvents;

use ChampionsLeagueClient\Game\Enums\EventType;
use ChampionsLeagueClient\Game\Response\Data\GoalData;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class GoalEvent extends BaseEvent implements ReadonlyInterface
{
    public function __construct(int $time, EventType $type, GoalData $data)
    {
        parent::__construct($time, $type, $data);
    }

    public function toArray(): array
    {
        return [
            'time' => $this->time,
            'type' => $this->type->value,
            'data' => $this->data->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            time: $data['time'],
            type: EventType::fromApiValue($data['type']),
            data: GoalData::fromArray($data['data']),
        );
    }
}
