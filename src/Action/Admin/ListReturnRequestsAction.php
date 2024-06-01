<?php
declare(strict_types=1);

namespace App\Action\Admin;

use Exception;

readonly class ListReturnRequestsAction
{
    public function __construct()
    {
    }

    public function __invoke(): array
    {
        try {
            return [];
        } catch (Exception $e) {
            throw new Exception('Error retrieving return requests');
        }
    }
}
