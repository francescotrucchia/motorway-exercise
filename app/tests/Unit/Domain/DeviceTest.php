<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Device;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DeviceTest extends TestCase
{
    /**
     * @test
     */
    public function it_create_a_device()
    {
        $userId = Uuid::uuid4()->toString();
        $deviceId = Uuid::uuid4()->toString();

        $device = new Device($deviceId, $userId);

        $this->assertInstanceOf(Device::class, $device);

        $this->assertEquals($userId, $device->getUserId());
        $this->assertEquals($deviceId, $device->getId());
    }
}