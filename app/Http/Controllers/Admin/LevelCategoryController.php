<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LevelCategory;
use App\Services\LevelCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LevelCategoryController extends Controller
{
    public function __construct(
        protected LevelCategoryService $levelCategoryService
    ) {
    }

    public function index(Request $request): View
    {
        $searchInput = [
            'levelCategoryName' => $request->input('levelCategoryName'),
        ];
        $levelCategories = $this->levelCategoryService->search($searchInput)->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.level_categories.index', ['levelCategories' => $levelCategories]);
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    // public function store(StoreCategoryRequest $request): RedirectResponse
    // {
    //     $validated = $request->validated();

    //     Category::create($validated);

    //     return redirect()->route('admin.categories.index')->with('status', 'カテゴリを追加しました');
    // }

    // public function edit(Category $category): View
    // {
    //     return view('admin.categories.edit', ['category' => $category]);
    // }

    // public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => 'unique:categories|required|string|max:255',
    //     ]);

    //     $category->update($request->all());

    //     return redirect()->route('admin.categories.index')->with('status', 'カテゴリを更新しました');
    // }

    // public function destroy(Category $category): RedirectResponse
    // {
    //     $category->delete();

    //     return redirect()->route('admin.categories.index')->with('status', 'カテゴリを削除しました');
    // }
}
