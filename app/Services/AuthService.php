<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthService implements AuthServiceInterface
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ];

        // Use createUser() not create()
        $user = $this->userRepository->createUser($userData);
        
        $user->sendEmailVerificationNotification();
        
        event(new Registered($user));

        return [
            'user' => $user,
            'message' => 'Registration successful. Please check your email for verification.'
        ];
    }
}