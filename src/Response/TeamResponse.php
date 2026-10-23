<?php

namespace ChampionsLeagueClient\Response;

use ChampionsLeagueClient\Interfaces\ReadonlyInterface;

final readonly class TeamResponse implements ReadonlyInterface
{
    public function __construct(
        public int $id,
        public string $name,
        public string $shortcut,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortcut' => $this->shortcut,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            name: $data['name'],
            shortcut: $data['shortcut'],
        );
    }
}
