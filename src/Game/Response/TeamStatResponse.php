<?php

namespace ChampionsLeagueClient\Game\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class TeamStatResponse implements ReadonlyInterface
{
    public function __construct(
        public float $shotEfficiency,
        public int $ppEfficiency,
        public int $shEfficiency,
        public int $ppCount,
        public int $shCount,
        public int $goals,
        public int $shotsAttempts,
        public int $shotsOnGoal,
        public int $shotsMissed,
        public int $shotsBlocked,
        public int $saves,
        public float $savesPercent,
        public int $ppGoals,
        public int $shGoals,
        public int $ppLosses,
        public int $penaltyMinutes,
        public int $faceOffsWon,
        public float $faceOffsWonPercent,
        public int $faceOffsTotal,
        public int $hits,
    ) {}

    public function toArray(): array
    {
        return [
            'shotEfficiency' => $this->shotEfficiency,
            'ppEfficiency' => $this->ppEfficiency,
            'shEfficiency' => $this->shEfficiency,
            'ppCount' => $this->ppCount,
            'shCount' => $this->shCount,
            'goals' => $this->goals,
            'shotsAttempts' => $this->shotsAttempts,
            'shotsOnGoal' => $this->shotsOnGoal,
            'shotsMissed' => $this->shotsMissed,
            'shotsBlocked' => $this->shotsBlocked,
            'saves' => $this->saves,
            'savesPercent' => $this->savesPercent,
            'ppGoals' => $this->ppGoals,
            'shGoals' => $this->shGoals,
            'ppLosses' => $this->ppLosses,
            'penaltyMinutes' => $this->penaltyMinutes,
            'faceOffsWon' => $this->faceOffsWon,
            'faceOffsWonPercent' => $this->faceOffsWonPercent,
            'faceOffsTotal' => $this->faceOffsTotal,
            'hits' => $this->hits,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            shotEfficiency: $data['shotEfficiency'],
            ppEfficiency: $data['ppEfficiency'],
            shEfficiency: $data['shEfficiency'],
            ppCount: $data['ppCount'],
            shCount: $data['shCount'],
            goals: $data['goals'],
            shotsAttempts: $data['shotsAttempts'],
            shotsOnGoal: $data['shotsOnGoal'],
            shotsMissed: $data['shotsMissed'],
            shotsBlocked: $data['shotsBlocked'],
            saves: $data['saves'],
            savesPercent: $data['savesPercent'],
            ppGoals: $data['ppGoals'],
            shGoals: $data['shGoals'],
            ppLosses: $data['ppLosses'],
            penaltyMinutes: $data['penaltyMinutes'],
            faceOffsWon: $data['faceOffsWon'],
            faceOffsWonPercent: $data['faceOffsWonPercent'],
            faceOffsTotal: $data['faceOffsTotal'],
            hits: $data['hits'],
        );
    }
}
