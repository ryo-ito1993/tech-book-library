<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Library;
use App\Services\UserService;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user1 = User::create(['name' => 'Yamada Taro', 'email' => 'test1@example.com', 'password' => 'password']);
        $user2 = User::create(['name' => 'Tanaka Taro', 'email' => 'test2@example.com', 'password' => 'password']);
        Library::create(['user_id' => $user1->id, 'system_id' => 'Tokyo_Shinagawa', 'system_name' => '東京都品川区']);
    }

    public function testSearchByName()
    {
        $result = UserService::search(['userName' => 'Yamada'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Yamada Taro', $result->first()->name);
    }


    public function testSearchByEmail()
    {
        $result = UserService::search(['email' => 'test1'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('test1@example.com', $result->first()->email);
    }

    public function testSearchByLibrary()
    {
        $result = UserService::search(['library' => '品川区'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('東京都品川区', $result->first()->library->system_name);
    }

    public function testSearchByLibraryNotRegistered()
    {
        $result = UserService::search(['library' => '未登録'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Tanaka Taro', $result->first()->name);
    }

    public function testSearchByMultiple()
    {
        $result = UserService::search(['userName' => 'Taro', 'email' => 'test1', 'library' => '品川区'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Yamada Taro', $result->first()->name);
        $this->assertEquals('test1@example.com', $result->first()->email);
        $this->assertEquals('東京都品川区', $result->first()->library->system_name);
    }
}
