# Task Management API
API REST développée avec Laravel permattant de gerer des taches et leur catégories.

L'application permet aux utilisateurs authentifiés de creer, consulter, modifier et supprimer leurs taches. Les administateurs disposen de droits supplemezntaires pour gerer l'ensemble des taches.

## Fonctionnalités principales

- Inscription et connexiondes utilisateurs
- Authentifacation avec Laravel Sanctum
- Gestion des taches
- Gestion des categories
- Gestion des roles utilisateurs
- Autorisation avec les policies Laravel
- Validation des données avec les form Requests
- Recherche de taches 
- Filtrage par status
- Filtrage par category
- Pagination des resultats 
- Gestion centralisée des exception
- Architecture Controller/ Service/ Repository

## Architecture du project

Le projet utilise une architecture en couches afin de séparé les responsabilité et de faciliter la maintenance du code.

Client
|
+-- Controller
|
+-- FormRequest
|
+-- Service
|
+-- Repository
|
+-- Model / Database

Structurer en dossier:

app/
+-- Exceptions/
+-- Http/
|   +-- Controllers/
|   +-- Requests/
|   +-- Resources/
+-- Models/
+-- Policies/
+-- Repositories/
+-- Services/

routes/
+-- api.php

database/
+-- migrations/
+-- seeders/

## Technologies utilisées

- PHP
- Laravel
- Laravel Herd
- Laravel Sanctum
- PostgrSQL
- REST API
- Eloquent ORM

## Prérequis

Avant d'installer le projet, assurez-vous d'avoir installé :

- PHP
- Composer
- PostgreSQL
- Laravel

## Installation