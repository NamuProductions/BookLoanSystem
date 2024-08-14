<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepository
{
    public function ofId(string $userId): ?User;
    public function save(User $user): void;
}
