<?php
declare(strict_types=1);

namespace App\Action\Admin;

use App\Service\ReturnRequestQueryServiceInterface;
use Exception;

readonly class ReturnRequestsAction
{
    public function __construct(private ReturnRequestQueryServiceInterface $returnRequestQueryService)
    {
    }

    public function __invoke(): array
    {
        try {
            return $this->returnRequestQueryService->returnRequests();
        } catch (Exception) {
            throw new Exception('Error retrieving return requests');
        }
    }
}
