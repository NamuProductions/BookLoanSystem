<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\User;
use App\Domain\ValueObject\UserName;

interface UserRepository
{
    public function save(User $user): void;
    public function findByUserName(UserName $username): ?User;
    public function findById(string $userId): ?User;
}
