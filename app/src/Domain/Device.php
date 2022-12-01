<?php

declare(strict_types=1);

namespace App\Domain;

class Device
{
    private $deviceId;
    private $userId;

    public function __construct(string $deviceId, string $userId)
    {
        $this->deviceId = $deviceId;
        $this->userId = $userId;
    }

    public function getId(): string
    {
        return $this->deviceId;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }

}