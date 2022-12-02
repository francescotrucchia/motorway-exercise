<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Route;
use App\Domain\Station;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Nonstandard\Uuid;

class RouteTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_a_route()
    {
        $id = Uuid::uuid4()->toString();
        $name = 'A14';
        $price = 0.7;

        $anconaStation = new Station(Uuid::uuid4()->toString(), 'Ancona');
        $milanoStation = new Station(Uuid::uuid4()->toString(), 'Milano');
        $bolognaStation = new Station(Uuid::uuid4()->toString(), 'Bologna');
        $cesenaStation = new Station(Uuid::uuid4()->toString(), 'Cesena');

        $route = new Route($id, $name, $price);
        $route->addStation($anconaStation, 0);
        $route->addStation($milanoStation, 400);
        $route->addStation($bolognaStation, 200);
        $route->addStation($cesenaStation, 150);


        $this->assertEquals($id, $route->getId());
        $this->assertEquals($name, $route->getName());
        $this->assertEquals($price, $route->getPrice());
        $this->assertCount(4, $route->getStations());
    }
}