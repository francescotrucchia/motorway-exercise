<?php

declare(strict_types=1);

namespace App\Domain;

class Route
{
    private string $id;
    private string $name;
    private array $stations = [];
    private float $price;

    public function __construct(string $id, string $name, float $price)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
    }

    public function addStation(Station $station, int $km)
    {
        $this->stations[$station->getId()] = $km;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getStations(): array
    {
        return $this->stations;
    }
}