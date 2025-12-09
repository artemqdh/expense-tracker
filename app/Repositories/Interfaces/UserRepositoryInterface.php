<?php

namespace App\Repositories\Interfaces;

use App\Models\User;

interface UserRepositoryInterface
{
    public function createUser(array $data): User;
    public function findUserByEmail(string $email): ?User;
    public function findUserById(int $id): ?User;
    public function updateUser(int $id, array $data): bool;
    public function deleteUser(int $id): bool;
    public function markEmailAsVerified(int $userId): bool;
    public function getUserWithExpenses(int $userId): ?User;
    public function getAllUsers();
    public function updatePassword(int $userId, string $password): bool;
}