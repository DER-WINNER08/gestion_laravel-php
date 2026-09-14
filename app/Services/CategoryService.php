<?php

namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function __construct(
        protected CategoryRepository $categoryRepository
    ) {}

    public function getAllCategories(): Collection
    {
        return $this->categoryRepository->getAllWithTasks();
    }

    public function createCategory(array $data): Category
    {
        return DB::transaction(function () use ($data) {
            return $this->categoryRepository->create($data);
        });
    }

    public function getCategoryById(int $id): Category
    {
        return $this->categoryRepository->findById($id);
    }

    public function deleteCategory(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $category = $this->categoryRepository->findById($id);
            return $this->categoryRepository->delete($category);
        });
    }
}