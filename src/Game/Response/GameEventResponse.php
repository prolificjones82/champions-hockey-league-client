<?php

namespace ChampionsLeagueClient\Game\Response;

use ChampionsLeagueClient\Game\Enums\EventType;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\GameEvents\GoalEvent;
use ChampionsLeagueClient\Game\Response\GameEvents\PenaltyEvent;
use ChampionsLeagueClient\Game\Response\GameEvents\ShootoutEvent;
use ChampionsLeagueClient\Game\Response\GameEvents\GoalkeeperChangeEvent;

final readonly class GameEventResponse implements ReadonlyInterface
{
    public function __construct(
        /**
         * @var array<int, GoalkeeperChangeEvent|GoalEvent|PenaltyEvent>
         */
        public array $period_one,
        /**
         * @var array<int, GoalkeeperChangeEvent|GoalEvent|PenaltyEvent>
         */
        public array $period_two,
        /**
         * @var array<int, GoalkeeperChangeEvent|GoalEvent|PenaltyEvent>
         */
        public array $period_three,
        /**
         * @var array<int, GoalkeeperChangeEvent|GoalEvent|PenaltyEvent>
         */
        public array $overtime,
        /**
         * @var array<int, ShootoutEvent>
         */
        public array $shootout,
        public int $last_event_time,
    ) {}

    /**
     * @param  array<int, array<string, mixed>>                         $events
     * @return array<int, GoalkeeperChangeEvent|GoalEvent|PenaltyEvent>
     */
    private static function mapEvents(array $events): array
    {
        return array_map(
            static fn(array $event) => match ($event['type'] ?? null) {
                'goalkeeperChange', EventType::goalkeeperChange => GoalkeeperChangeEvent::fromArray($event),
                'goal', EventType::goal => GoalEvent::fromArray($event),
                'penalty', EventType::penalty => PenaltyEvent::fromArray($event),
                default => throw new \InvalidArgumentException('Unknown event type'),
            },
            $events,
        );
    }

    public function toArray(): array
    {
        return [
            'period_one' => array_map(
                static fn(GoalkeeperChangeEvent|GoalEvent|PenaltyEvent $event): array => $event->toArray(),
                $this->period_one,
            ),
            'period_two' => array_map(
                static fn(GoalkeeperChangeEvent|GoalEvent|PenaltyEvent $event): array => $event->toArray(),
                $this->period_two,
            ),
            'period_three' => array_map(
                static fn(GoalkeeperChangeEvent|GoalEvent|PenaltyEvent $event): array => $event->toArray(),
                $this->period_three,
            ),
            'overtime' => array_map(
                static fn(GoalkeeperChangeEvent|GoalEvent|PenaltyEvent $event): array => $event->toArray(),
                $this->overtime,
            ),
            'shootout' => array_map(
                static fn(ShootoutEvent $event): array => $event->toArray(),
                $this->shootout,
            ),
            'last_event_time' => $this->last_event_time,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            period_one: self::mapEvents($data['1'] ?? $data['period_one'] ?? []),
            period_two: self::mapEvents($data['2'] ?? $data['period_two'] ?? []),
            period_three: self::mapEvents($data['3'] ?? $data['period_three'] ?? []),
            overtime: self::mapEvents($data['overtime'] ?? []),
            shootout: array_map(
                ShootoutEvent::fromArray(...),
                $data['shootout'] ?? [],
            ),
            last_event_time: $data['last_event_time'],
        );
    }
}
