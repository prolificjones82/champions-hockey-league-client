<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use ChampionsLeagueClient\Game\Enums\PenaltyType;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameInfo\RosterPlayerResponse;

final readonly class PenaltyData implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public string $team,
        public int $createTime,
        public RosterPlayerResponse $disciplinedPlayer,
        public ?RosterPlayerResponse $replacedPlayer,
        public ?RosterPlayerResponse $fouledPlayer,
        public ?TimeData $from,
        public ?TimeData $to,
        public int $duration,
        public bool $shooting,
        public bool $disciplined_functionary,
        public bool $disciplined_bm,
        public PenaltyType $codename,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'team' => $this->team,
            'createTime' => $this->createTime,
            'disciplinedPlayer' => $this->disciplinedPlayer->toArray(),
            'replacedPlayer' => ($this->replacedPlayer) ? $this->replacedPlayer->toArray() : null,
            'fouledPlayer' => ($this->fouledPlayer) ? $this->fouledPlayer->toArray() : null,
            'time' => [
                'from' => $this->from?->toArray(),
                'to' => $this->to?->toArray(),
            ],
            'duration' => $this->duration,
            'shooting' => $this->shooting,
            'disciplined_functionary' => $this->disciplined_functionary,
            'disciplined_bm' => $this->disciplined_bm,
            'codename' => $this->codename->value,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            team: $data['team'],
            createTime: $data['createTime'],
            disciplinedPlayer: RosterPlayerResponse::fromArray($data['disciplinedPlayer']),
            replacedPlayer: isset($data['replacedPlayer']) ? RosterPlayerResponse::fromArray($data['replacedPlayer']) : null,
            fouledPlayer: isset($data['fouledPlayer']) ? RosterPlayerResponse::fromArray($data['fouledPlayer']) : null,
            from: isset($data['time']['from']) ? TimeData::fromArray($data['time']['from']) : null,
            to: isset($data['time']['to']) ? TimeData::fromArray($data['time']['to']) : null,
            duration: $data['duration'],
            shooting: $data['shooting'],
            disciplined_functionary: $data['disciplined_functionary'],
            disciplined_bm: $data['disciplined_bm'],
            codename: PenaltyType::from($data['codename']),
        );
    }
}
