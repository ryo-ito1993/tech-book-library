<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LevelCategory;
use App\Services\LevelCategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\Admin\StoreLevelCategoryRequest;
use App\Http\Requests\Admin\UpdateLevelCategoryRequest;

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
        return view('admin.level_categories.create');
    }

    public function store(StoreLevelCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        LevelCategory::create($validated);

        return redirect()->route('admin.level_categories.index')->with('status', 'レベルカテゴリを追加しました');
    }

    public function edit(LevelCategory $levelCategory): View
    {
        return view('admin.level_categories.edit', ['levelCategory' => $levelCategory]);
    }

    public function update(UpdateLevelCategoryRequest $request, LevelCategory $levelCategory): RedirectResponse
    {
        $validated = $request->validated();

        $levelCategory->update($validated);

        return redirect()->route('admin.level_categories.index')->with('status', 'レベルカテゴリを更新しました');
    }

    public function destroy(LevelCategory $levelCategory): RedirectResponse
    {
        $levelCategory->delete();

        return redirect()->route('admin.level_categories.index')->with('status', 'レベルカテゴリを削除しました');
    }
}
