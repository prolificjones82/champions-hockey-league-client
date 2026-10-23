<?php

namespace ChampionsLeagueClient\Standings\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class TeamResponse implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public int $partTeamId,
        public string $name,
        public string $teamOriginal,
        public string $shortcut,
        public int $position,
        public int $points,
        public int $games,
        public int $wins,
        public int $winsOt,
        public int $winsReg,
        public int $ties,
        public int $lostsOt,
        public int $losts,
        public int $score1,
        public int $score2,
        public int $goalsAway,
        public ?string $last10,
        public int $onlyOTwins,
        public int $onlySOwins,
        public int $onlyOTlosts,
        public int $onlySOlosts,
        public bool $qualified,
        public string $ppg,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'partTeamId' => $this->partTeamId,
            'name' => $this->name,
            'teamOriginal' => $this->teamOriginal,
            'shortcut' => $this->shortcut,
            'position' => $this->position,
            'points' => $this->points,
            'games' => $this->games,
            'wins' => $this->wins,
            'winsOt' => $this->winsOt,
            'winsReg' => $this->winsReg,
            'ties' => $this->ties,
            'lostsOt' => $this->lostsOt,
            'losts' => $this->losts,
            'score1' => $this->score1,
            'score2' => $this->score2,
            'goalsAway' => $this->goalsAway,
            'last10' => $this->last10,
            'onlyOTwins' => $this->onlyOTwins,
            'onlySOwins' => $this->onlySOwins,
            'onlyOTlosts' => $this->onlyOTlosts,
            'onlySOlosts' => $this->onlySOlosts,
            'qualified' => $this->qualified,
            'ppg' => $this->ppg,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            partTeamId: $data['partTeamId'],
            name: $data['name'],
            teamOriginal: $data['teamOriginal'],
            shortcut: $data['shortcut'],
            position: $data['position'],
            points: $data['points'],
            games: $data['games'],
            wins: $data['wins'],
            winsOt: $data['winsOt'],
            winsReg: $data['winsReg'],
            ties: $data['ties'],
            lostsOt: $data['lostsOt'],
            losts: $data['losts'],
            score1: $data['score1'],
            score2: $data['score2'],
            goalsAway: $data['goalsAway'],
            last10: $data['last10'] ?? null,
            onlyOTwins: $data['onlyOTwins'],
            onlySOwins: $data['onlySOwins'],
            onlyOTlosts: $data['onlyOTlosts'],
            onlySOlosts: $data['onlySOlosts'],
            qualified: $data['qualified'],
            ppg: $data['ppg'],
        );
    }
}
