<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HealthCheck
{
    public function __invoke(): Response
    {
        return new JsonResponse(['status' => 'ok']);
    }
}
