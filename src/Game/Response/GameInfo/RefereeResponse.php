<?php

namespace ChampionsLeagueClient\Game\Response\GameInfo;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class RefereeResponse implements ReadonlyInterface
{
    public function __construct(
        public string $headReferee1,
        public string $headReferee2,
        public string $lineReferee1,
        public string $lineReferee2,
    ) {}

    public function toArray(): array
    {
        return [
            'headReferee1' => $this->headReferee1,
            'headReferee2' => $this->headReferee2,
            'lineReferee1' => $this->lineReferee1,
            'lineReferee2' => $this->lineReferee2,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            headReferee1: $data['headReferee1'],
            headReferee2: $data['headReferee2'],
            lineReferee1: $data['lineReferee1'],
            lineReferee2: $data['lineReferee2'],
        );
    }
}
