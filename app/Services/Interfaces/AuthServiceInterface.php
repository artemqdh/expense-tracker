<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    public function register(array $data): array;
}