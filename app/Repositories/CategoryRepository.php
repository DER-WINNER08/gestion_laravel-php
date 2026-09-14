<?php

namespace App\Repositories;

use App\Models\category;
use Illuminate\Database\Eloquent\Collection;

class CategoryRepository
{
    public function getAllWithTasks(): Collection
    {
        return category::with('tasks')->get();
    }

    public function create(array $data): category
    {
        return category::create($data);
    }

    public function findById(int $id): category
    {
        return category::with('tasks')->findOrFail($id);
    }

    public function delete(category $category): bool
    {
        return $category->delete();
    }
}