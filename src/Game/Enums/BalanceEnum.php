<?php

namespace ChampionsLeagueClient\Game\Enums;

enum BalanceEnum: string
{
    case EQ = 'Equal Strength';
    case PP1 = 'Powerplay Goal (5 on 4)';
    case PP2 = 'Powerplay Goal (5 on 3)';
    case SH1 = 'Shorthanded Goal (5 on 4)';
    case SH2 = 'Shorthanded Goal (5 on 3)';
    case PS = 'Penalty Shot';
    case GWS = 'Shootout Winner';
}
