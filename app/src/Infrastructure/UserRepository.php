<?php

declare(strict_types=1);

namespace App\Infrastructure;

use PDO;

final class UserRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function monthlyDue(string $userId, int $month, int $year): float
    {
        $startDate = new \DateTimeImmutable(sprintf('%s-%s-1 00:00:00', $year, $month));
        $daysOfMonth = $startDate->format('t');
        $endDate = new \DateTimeImmutable(sprintf('%s-%s-%s 00:00:00', $year, $month, $daysOfMonth));

        $stm = $this->connection->prepare('
            --SELECT d.user_id, d.id as device_id, enter_station.name as enter_station_name, exit_station.name as exit_station_name, dl.enter_date, dl.exit_date, route_station_exit.km as km_enter, route_station_enter.km as km_exit, ABS(route_station_exit.km - route_station_enter.km) as distance, r.price as price
            SELECT SUM(ABS(route_station_exit.km - route_station_enter.km)*r.price) as sum
            FROM DEVICE_LOG dl, DEVICE d, STATION enter_station, STATION exit_station, ROUTE_STATION route_station_enter, ROUTE_STATION route_station_exit, ROUTE r
            WHERE dl.device_id = d.id 
            AND dl.enter_station_id = enter_station.id 
            AND dl.exit_station_id = exit_station.id 
            AND dl.route_id = route_station_enter.route_id 
            AND dl.enter_station_id = route_station_enter.station_id
            AND dl.route_id = route_station_exit.route_id 
            AND dl.exit_station_id = route_station_exit.station_id
            AND dl.route_id = r.id
            AND d.user_id = :user_id
            AND dl.enter_date >= :start_date
            AND dl.exit_date <= :end_date
        ');

        $stm->execute([':user_id' => $userId, ':start_date' => $startDate->format('Y-m-d'), ':end_date' => $endDate->format('Y-m-d')]);
        $results = $stm->fetch(PDO::FETCH_ASSOC);

        if (is_null($results['sum'])) {
            return 0;
        }

        return $results['sum'];
    }
}