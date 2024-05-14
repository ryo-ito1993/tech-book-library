<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Category;
use App\Services\CategoryService;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testSearchByName()
    {
        Category::create(['name' => 'Laravel']);
        Category::create(['name' => 'Ruby']);

        $resultQuery = CategoryService::search(['categoryName' => 'Lara']);
        $result = $resultQuery->get();

        $this->assertCount(1, $result);
        $this->assertEquals('Laravel', $result->first()->name);
    }
}
