<?php

namespace App\OpenApi;
use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'Task Management API',
    version: '1.0.0',
    description: "API REST de gestion des tâches et des catégories."
)]

#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Sanctum Token'
)]
class OpenApi
{
}