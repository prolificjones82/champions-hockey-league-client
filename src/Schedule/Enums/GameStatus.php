<?php

namespace ChampionsLeagueClient\Schedule\Enums;

/**
 * Defined here: https://gamestatschlclubs.docs.apiary.io/#reference/game-schedule/get-league-schedule
 */
enum GameStatus: string
{
    public static function fromApiValue(string $value): self
    {
        return match ($value) {
            'BEFORE_MATCH' => self::BEFORE_MATCH,
            'LIVE' => self::LIVE,
            'AFTER_MATCH' => self::AFTER_MATCH,
            'POSTPONED' => self::POSTPONED,
            'INTERUPTED', 'INTERRUPTED' => self::INTERUPTED,
            'CANCELLED', 'CANCELED' => self::CANCELLED,
            default => self::from($value),
        };
    }

    case BEFORE_MATCH = 'Fixture';
    case LIVE = 'Live';
    case AFTER_MATCH = 'Result';
    case POSTPONED = 'Postponed';
    case INTERUPTED = 'Interupted';
    case CANCELLED = 'Cancelled';
}
