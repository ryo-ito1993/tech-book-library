<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\LevelCategory;
use App\Services\LevelCategoryService;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class LevelCategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchByName(): void
    {
        LevelCategory::create(['name' => '入門']);
        LevelCategory::create(['name' => '中級']);

        $resultQuery = LevelCategoryService::search(['levelCategoryName' => '入']);
        /** @var Collection|LevelCategory[] $result */
        $result = $resultQuery->get();

        $this->assertCount(1, $result);
        $this->assertSame('入門', $result->first()->name);
    }
}
