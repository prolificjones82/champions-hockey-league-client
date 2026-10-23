<?php

namespace ChampionsLeagueClient\Standings\Enums;

/**
 * Defined here: https://gamestatschlclubs.docs.apiary.io/#reference/standings-&-rankings/standings/get-league-schedule
 */
enum TableDefinition: string
{
    public const score1 = 'Goals Scored';
    public const score2 = 'Goals Against';
}
