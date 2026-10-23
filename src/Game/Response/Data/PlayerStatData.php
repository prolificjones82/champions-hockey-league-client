<?php

namespace ChampionsLeagueClient\Game\Response\Data;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class PlayerStatData implements ReadonlyInterface
{
    public function __construct(
        public string $teamShortcut,
        public int $games,
        public PlayerStatPointData $goals,
        public PlayerStatPointData $assists,
        public PlayerStatPointData $points,
        public int $penaltyMinutes,
        public int $positive,
        public int $negative,
        public float $pointsPerGame,
        public float $shotEfficiency,
        public float $penaltyMinutesPerGame,
        public int $ppGoals,
        public int $shGoals,
        public int $gwGoals,
        public int $shotsAttempts,
        public PlayerStatPointData $shotsOnGoal,
        public int $shotsMissed,
        public int $shotsBlocked,
        public int $blockedShotsByPlayer,
        public int $faceoffsWin,
        public int $faceoffsLosses,
        public int $faceoffsCount,
        public int $timeonice,
        public int $timeoniceEQ,
        public int $timeonicePP,
        public int $timeoniceSH,
        public int $hits,
    ) {}

    public function toArray(): array
    {
        return [
            'teamShortcut' => $this->teamShortcut,
            'games' => $this->games,
            'goals' => $this->goals->toArray(),
            'assists' => $this->assists->toArray(),
            'points' => $this->points->toArray(),
            'penaltyMinutes' => $this->penaltyMinutes,
            'positive' => $this->positive,
            'negative' => $this->negative,
            'pointsPerGame' => $this->pointsPerGame,
            'shotEfficiency' => $this->shotEfficiency,
            'penaltyMinutesPerGame' => $this->penaltyMinutesPerGame,
            'ppGoals' => $this->ppGoals,
            'shGoals' => $this->shGoals,
            'gwGoals' => $this->gwGoals,
            'shotsAttempts' => $this->shotsAttempts,
            'shotsOnGoal' => $this->shotsOnGoal->toArray(),
            'shotsMissed' => $this->shotsMissed,
            'shotsBlocked' => $this->shotsBlocked,
            'blockedShotsByPlayer' => $this->blockedShotsByPlayer,
            'faceoffsWin' => $this->faceoffsWin,
            'faceoffsLosses' => $this->faceoffsLosses,
            'faceoffsCount' => $this->faceoffsCount,
            'timeonice' => $this->timeonice,
            'timeoniceEQ' => $this->timeoniceEQ,
            'timeonicePP' => $this->timeonicePP,
            'timeoniceSH' => $this->timeoniceSH,
            'hits' => $this->hits,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            teamShortcut: $data['teamShortcut'],
            games: $data['games'] ?? 0,
            goals: PlayerStatPointData::fromArray($data['goals'] ?? []),
            assists: PlayerStatPointData::fromArray($data['assists'] ?? []),
            points: PlayerStatPointData::fromArray($data['points'] ?? []),
            penaltyMinutes: $data['penaltyMinutes'] ?? 0,
            positive: $data['positive'] ?? 0,
            negative: $data['negative'] ?? 0,
            pointsPerGame: $data['pointsPerGame'] ?? 0,
            shotEfficiency: $data['shotEfficiency'] ?? 0,
            penaltyMinutesPerGame: $data['penaltyMinutesPerGame'] ?? 0,
            ppGoals: $data['ppGoals'] ?? 0,
            shGoals: $data['shGoals'] ?? 0,
            gwGoals: $data['gwGoals'] ?? 0,
            shotsAttempts: $data['shotsAttempts'] ?? 0,
            shotsOnGoal: PlayerStatPointData::fromArray($data['shotsOnGoal'] ?? []),
            shotsMissed: $data['shotsMissed'] ?? 0,
            shotsBlocked: $data['shotsBlocked'] ?? 0,
            blockedShotsByPlayer: $data['blockedShotsByPlayer'] ?? 0,
            faceoffsWin: $data['faceoffsWin'] ?? 0,
            faceoffsLosses: $data['faceoffsLosses'] ?? 0,
            faceoffsCount: $data['faceoffsCount'] ?? 0,
            timeonice: $data['timeonice'] ?? 0,
            timeoniceEQ: $data['timeoniceEQ'] ?? 0,
            timeonicePP: $data['timeonicePP'] ?? 0,
            timeoniceSH: $data['timeoniceSH'] ?? 0,
            hits: $data['hits'] ?? 0,
        );
    }
}
