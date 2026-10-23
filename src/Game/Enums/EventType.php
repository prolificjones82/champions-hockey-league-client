<?php

namespace ChampionsLeagueClient\Game\Enums;

/**
 * Defined here: https://gamestatschlclubs.docs.apiary.io/#reference/game-stats/game-events
 */
enum EventType: string
{
    public const goal = 'Goal';
    public const penalty = 'Penalty';
    public const goalkeeperChange = 'Goalkeeper Change';

    /**
     * Normalize API discriminators and labels to their corresponding enum case.
     */
    public static function fromApiValue(string $value): self
    {
        return match ($value) {
            'goal' => self::GOAL_EVENT,
            'penalty' => self::PENALTY_EVENT,
            'goalkeeperChange' => self::GOALKEEPER_CHANGE_EVENT,
            'shootout' => self::SHOOTOUT_EVENT,
            default => self::from($value),
        };
    }
    case GOAL_EVENT = 'Goal';
    case PENALTY_EVENT = 'Penalty';
    case GOALKEEPER_CHANGE_EVENT = 'Goalkeeper Change';
    case SHOOTOUT_EVENT = 'Shootout';
}
