<?php

namespace App\Domain\Repository;

use App\Domain\Model\User;

interface UserRepository
{
    public function save(User $user): void;
}