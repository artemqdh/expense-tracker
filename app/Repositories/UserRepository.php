<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new user
     */
    public function createUser(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * Find user by email
     */
    public function findUserByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Find user by ID
     */
    public function findUserById(int $id): ?User
    {
        return $this->model->find($id);
    }

    /**
     * Update user data
     */
    public function updateUser(int $id, array $data): bool
    {
        $user = $this->model->findOrFail($id);
        return $user->update($data);
    }

    /**
     * Delete a user
     */
    public function deleteUser(int $id): bool
    {
        return $this->model->findOrFail($id)->delete();
    }

    /**
     * Mark user's email as verified
     */
    public function markEmailAsVerified(int $userId): bool
    {
        $user = $this->model->findOrFail($userId);
        return $user->forceFill([
            'email_verified_at' => now(),
        ])->save();
    }

    /**
     * Get user with their expenses (eager loading)
     */
    public function getUserWithExpenses(int $userId): ?User
    {
        return $this->model->with('expenses')->find($userId);
    }

    /**
     * Get all users (for admin purposes)
     */
    public function getAllUsers()
    {
        return $this->model->all();
    }

    /**
     * Update user's password
     */
    public function updatePassword(int $userId, string $password): bool
    {
        $user = $this->model->findOrFail($userId);
        return $user->update([
            'password' => bcrypt($password)
        ]);
    }
}