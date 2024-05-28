<?php

namespace Tests\Feature\Controllers\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Prefecture;
use App\Library\calilApiLibrary;

class LibraryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

        /**
     * @var calilApiLibrary
     */
    protected $calilApiLibrary;

    protected function setUp(): void
    {
        parent::setUp();

        $this->calilApiLibrary = $this->mock(calilApiLibrary::class, static function ($mock) {
            $mock->shouldReceive('getLibrariesBySystemId')
                ->andReturn([
                    [
                        'systemid' => 'test_systemid',
                        'systemname' => 'test_systemname',
                        'libid' => 'test_libid',
                        'libkey' => 'test_libkey',
                        'short' => 'test_short',
                        'formal' => 'test_formal',
                    ]
                ]);
        });
    }

    public function testCreate(): void
    {
        $user = User::factory()->create();
        $prefectures = Prefecture::factory()->count(2)->create();

        $this->actingAs($user);

        $response = $this->get(route('user.library.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.libraries.create');
        $response->assertViewHas('prefectures', $prefectures);
    }

    public function testCreateWithUserLibrary(): void
    {
        $user = User::factory()->create();
        $prefectures = Prefecture::factory()->count(2)->create();
        $userLibrary = $user->library()->create([
            'system_id' => 'test_systemid',
            'system_name' => 'test_systemname',
        ]);

        $this->actingAs($user);

        $response = $this->get(route('user.library.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.libraries.create');
        $response->assertViewHas('userLibrary', $userLibrary);
        $response->assertViewHas('prefectures', $prefectures);
        $response->assertViewHas('userLibraries', [
            [
                'systemid' => 'test_systemid',
                'systemname' => 'test_systemname',
                'libid' => 'test_libid',
                'libkey' => 'test_libkey',
                'short' => 'test_short',
                'formal' => 'test_formal',
            ]
        ]);
    }

    public function testStore(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $data = [
            'systemid' => 'test_systemid',
            'systemname' => 'test_systemname',
        ];

        $response = $this->post(route('user.library.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHas('status', 'お気に入り図書館を登録しました');

        $this->assertDatabaseHas('libraries', [
            'user_id' => $user->id,
            'system_id' => 'test_systemid',
            'system_name' => 'test_systemname',
        ]);
    }

    public function testDestroy(): void
    {
        $user = User::factory()->create();
        $library = $user->library()->create([
            'system_id' => 'test_systemid',
            'system_name' => 'test_systemname',
        ]);

        $this->actingAs($user);

        $response = $this->delete(route('user.library.destroy', $library));

        $response->assertRedirect();
        $response->assertSessionHas('status', 'お気に入り図書館を削除しました');

        $this->assertDatabaseMissing('libraries', [
            'user_id' => $user->id,
            'system_id' => 'test_systemid',
            'system_name' => 'test_systemname',
        ]);
    }
}
