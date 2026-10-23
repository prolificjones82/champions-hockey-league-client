<?php

namespace ChampionsLeagueClient\Game\Response;

use DateTime;
use ChampionsLeagueClient\Constants;
use ChampionsLeagueClient\Interfaces\ReadonlyInterface;
use ChampionsLeagueClient\Game\Response\Data\PlayerStatData;

final readonly class PlayerStatResponse implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public string $firstname,
        public string $surname,
        public DateTime $birthdate,
        public bool $active,
        public string $position,
        public string $stick,
        public int $weight,
        public int $height,
        public int $jersey,
        public string $birthCountry,
        public string $nationality,
        public string $nationalityShort,
        public PlayerStatData $statistics,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'firstname' => $this->firstname,
            'surname' => $this->surname,
            'birthdate' => $this->birthdate->format(Constants::DATE_FORMAT),
            'active' => $this->active,
            'position' => $this->position,
            'stick' => $this->stick,
            'weight' => $this->weight,
            'height' => $this->height,
            'jersey' => $this->jersey,
            'birthCountry' => $this->birthCountry,
            'nationality' => $this->nationality,
            'nationalityShort' => $this->nationalityShort,
            'statistics' => $this->statistics->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            firstname: $data['firstname'],
            surname: $data['surname'],
            birthdate: new DateTime($data['birthdate']),
            active: $data['active'],
            position: $data['position'],
            stick: $data['stick'],
            weight: $data['weight'],
            height: $data['height'],
            jersey: $data['jersey'],
            birthCountry: $data['birthCountry'],
            nationality: $data['nationality'],
            nationalityShort: $data['nationalityShort'],
            statistics: PlayerStatData::fromArray($data['statistics']),
        );
    }
}
