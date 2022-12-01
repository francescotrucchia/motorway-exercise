<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Route;
use App\Domain\Station;
use PDO;
use Ramsey\Uuid\Nonstandard\Uuid;

class RouteRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Route $route): void
    {
        $query = 'INSERT INTO ROUTE VALUES (:id, :name, :price)';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $route->getId(), ':name' => $route->getName(), ':price' => $route->getPrice()]);

        foreach($route->getStations() as $stationId => $km)
        {
            $query = 'INSERT INTO ROUTE_STATION VALUES (:id, :routeId, :stationId, :km)';

            $stm = $this->connection->prepare($query);
            $stm->execute([':id' => Uuid::uuid4()->toString(), ':routeId' => $route->getId(), ':stationId' => $stationId, ':km' => $km]);
        }
    }

    public function load(string $id): Route
    {
        $query = 'SELECT * FROM ROUTE WHERE id = :id';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $id]);

        $result = $stm->fetch(PDO::FETCH_ASSOC);

        return new Route($result['id'], $result['name'], $result['price']);
    }
}