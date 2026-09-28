<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

class AuthDocumentation
{
   #[OA\Post(
    path: '/api/register',
    summary: 'Créer un nouveau compte',
    description: "Crée un utilisateur et retourne un token d'authentification.",
    tags: ['Authentication'],

    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['name', 'email', 'password'],
            properties: [
                new OA\Property(
                    property: 'name',
                    type: 'string',
                    example: 'John Doe'
                ),
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    format: 'email',
                    example: 'john@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
                new OA\Property(
                    property: 'role',
                    type: 'string',
                    example: 'user'
                ),
            ]
        )
    ),

    responses: [
        new OA\Response(
            response: 201,
            description: 'Compte créé avec succès'
        ),
        new OA\Response(
            response: 422,
            description: 'Données de validation invalides'
        ),
    ]
)]

public function register(): void
{
}

#[OA\Post(
    path: '/api/login',
    summary: 'Connecter un utilisateur',
    description: 'Authentifie un utilisateur et retourne un token Sanctum.',
    tags: ['Authentication'],

    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(
                    property: 'email',
                    type: 'string',
                    format: 'email',
                    example: 'john@example.com'
                ),
                new OA\Property(
                    property: 'password',
                    type: 'string',
                    format: 'password',
                    example: 'password123'
                ),
            ]
        )
    ),

    responses: [
        new OA\Response(
            response: 200,
            description: 'Connexion réussie'
        ),
        new OA\Response(
            response: 401,
            description: 'Identifiants invalides'
        ),
        new OA\Response(
            response: 422,
            description: 'Données de validation invalides'
        ),
    ]
)]
public function login(): void
{
}

#[OA\Post(
    path: '/api/logout',
    summary: "Déconnecter l'utilisateur",
    description: "Révoque le token Sanctum de l'utilisateur authentifié.",
    tags: ['Authentication'],
    security: [
        ['bearerAuth' => []]
    ],

    responses: [
        new OA\Response(
            response: 200,
            description: 'Déconnexion réussie'
        ),
        new OA\Response(
            response: 401,
            description: 'Utilisateur non authentifié'
        ),
    ]
)]
public function logout(): void
{
}
}