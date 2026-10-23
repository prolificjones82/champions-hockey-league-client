<?php

namespace ChampionsLeagueClient\Game\Enums;

/**
 * Defined here: https://gamestatschlclubs.docs.apiary.io/#reference/game-stats/game-events
 */
enum PenaltyType: string
{
    public const ABUSE = 'Abuse of officials';
    public const BITE = 'Biting';
    public const BOARD = 'Boarding';
    public const BROKEN = 'Playing with a broken stick';
    public const BE = 'Butt-ending';
    public const CHG = 'Charging';
    public const CFB = 'Checking from behind';
    public const HC = 'Checking to the head or neck';
    public const CLIP = 'Clipping';
    public const CLOSE_PUCK = 'Closing the hand on the puck';
    public const CC = 'Cross-checking';
    public const DANGEQ = 'Dangerous equipment';
    public const DOG = 'Delay of game';
    public const EMB = 'Diving / embellishment';
    public const ELB = 'Elbowing';
    public const FACEOFF = 'Face-off violations';
    public const FIGHT = 'Fighting';
    public const HEADBUTT = 'Head-butting';
    public const HS = 'High-sticking';
    public const HOLD = 'Holding';
    public const HOLDS = 'Holding the stick';
    public const HOOK = 'Hooking';
    public const ILLEQ = 'Illegal equipment';
    public const ILLEGAL_HIT = 'Illegal hit';
    public const INT = 'Interference';
    public const KNEE = 'Kneeing';
    public const KICK = 'Kicking';
    public const LEAVE_PB = 'Leaving the penalty box prematurely';
    public const NO_HELMET = 'Playing without a helmet';
    public const HAIR_PULL = 'Pulling hair, helmet, or cage';
    public const REFUSE_START = 'Refusing to start play';
    public const ROUGH = 'Roughing';
    public const SLASH = 'Slashing';
    public const SLEW = 'Slew-footing';
    public const SPEAR = 'Spearing';
    public const SPIT = 'Spitting';
    public const TAUNT = 'Taunting';
    public const OFFICIAL_ON_ICE = 'Team official entering the playing area';
    public const THROW = 'Throwing a stick or object';
    public const TMM = 'Too many men';
    public const TRIP = 'Tripping';
    public const UNS = 'Unsportsmanlike conduct';
    public const ILLEGAL_HIT_WOMEN = 'Illegal hit (women)';
    case ABUSE_TYPE = 'Abuse of officials';
    case BITE_TYPE = 'Biting';
    case BOARD_TYPE = 'Boarding';
    case BROKEN_TYPE = 'Playing with a broken stick';
    case BE_TYPE = 'Butt-ending';
    case CHG_TYPE = 'Charging';
    case CFB_TYPE = 'Checking from behind';
    case HC_TYPE = 'Checking to the head or neck';
    case CLIP_TYPE = 'Clipping';
    case CLOSE_PUCK_TYPE = 'Closing the hand on the puck';
    case CC_TYPE = 'Cross-checking';
    case DANGEQ_TYPE = 'Dangerous equipment';
    case DOG_TYPE = 'Delay of game';
    case EMB_TYPE = 'Diving / embellishment';
    case ELB_TYPE = 'Elbowing';
    case FACEOFF_TYPE = 'Face-off violations';
    case FIGHT_TYPE = 'Fighting';
    case HEADBUTT_TYPE = 'Head-butting';
    case HS_TYPE = 'High-sticking';
    case HOLD_TYPE = 'Holding';
    case HOLDS_TYPE = 'Holding the stick';
    case HOOK_TYPE = 'Hooking';
    case ILLEQ_TYPE = 'Illegal equipment';
    case ILLEGAL_HIT_TYPE = 'Illegal hit';
    case INT_TYPE = 'Interference';
    case KNEE_TYPE = 'Kneeing';
    case KICK_TYPE = 'Kicking';
    case LEAVE_PB_TYPE = 'Leaving the penalty box prematurely';
    case NO_HELMET_TYPE = 'Playing without a helmet';
    case HAIR_PULL_TYPE = 'Pulling hair, helmet, or cage';
    case REFUSE_START_TYPE = 'Refusing to start play';
    case ROUGH_TYPE = 'Roughing';
    case SLASH_TYPE = 'Slashing';
    case SLEW_TYPE = 'Slew-footing';
    case SPEAR_TYPE = 'Spearing';
    case SPIT_TYPE = 'Spitting';
    case TAUNT_TYPE = 'Taunting';
    case OFFICIAL_ON_ICE_TYPE = 'Team official entering the playing area';
    case THROW_TYPE = 'Throwing a stick or object';
    case TMM_TYPE = 'Too many men';
    case TRIP_TYPE = 'Tripping';
    case UNS_TYPE = 'Unsportsmanlike conduct';
    case ILLEGAL_HIT_WOMEN_TYPE = 'Illegal hit (women)';
}
