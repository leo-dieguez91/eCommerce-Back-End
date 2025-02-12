<?php

namespace App\Services\User;

use App\DTOs\User\UserDTO;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAll();
    }

    public function getUserById($id)
    {
        return $this->userRepository->findById($id);
    }

    public function createUser(UserDTO $userDTO)
    {
        $data = $userDTO->toArray();
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    public function updateUser($id, UserDTO $userDTO)
    {
        $user = $this->userRepository->findById($id);
        $data = $userDTO->toArray();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user, $data);
    }

    public function deleteUser($id)
    {
        $user = $this->userRepository->findById($id);
        return $this->userRepository->delete($user);
    }
} 