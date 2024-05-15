<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Category;
use App\Services\CategoryService;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Collection;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchByName(): void
    {
        Category::create(['name' => 'Laravel']);
        Category::create(['name' => 'Ruby']);

        $resultQuery = CategoryService::search(['categoryName' => 'Lara']);
        /** @var Collection|Category[] $result */
        $result = $resultQuery->get();

        $this->assertCount(1, $result);
        $this->assertSame('Laravel', $result->first()->name);
    }
}
