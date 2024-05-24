<?php

namespace App\Service;

use App\Domain\Model\User;

interface SessionManagerInterface
{
    public function startSession(User $user): void;
    public function endSession(): void;
    public function isAuthenticated(): bool;
    public function getUser(): ?User;
}
