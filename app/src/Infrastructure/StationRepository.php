<?php

declare(strict_types=1);

namespace App\Infrastructure;

use App\Domain\Station;
use PDO;

final class StationRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function insert(Station $station): void
    {
        $query = 'INSERT INTO STATION VALUES (:id, :name)';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $station->getId(), ':name' => $station->getName()]);
    }

    public function load(string $id): ?station
    {
        $query = 'SELECT * FROM STATION WHERE id = :id';

        $stm = $this->connection->prepare($query);
        $stm->execute([':id' => $id]);

        $result = $stm->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        return new Station($result['id'], $result['name']);
    }

    public function all(): array
    {
        $query = 'SELECT * FROM STATION';

        $stm = $this->connection->prepare($query);
        $stm->execute();

        $results = $stm->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function(array $result){
            return new Station($result['id'], $result['name']);
        }, $results);
    }
}