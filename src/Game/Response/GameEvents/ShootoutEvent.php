<?php

namespace ChampionsLeagueClient\Game\Response\GameEvents;

use ChampionsLeagueClient\Game\Enums\EventType;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\Data\ShootoutData;

final readonly class ShootoutEvent extends BaseEvent implements ReadonlyInterface
{
    public function __construct(int $time, EventType $type, ShootoutData $data)
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
            data: ShootoutData::fromArray($data['data']),
        );
    }
}
