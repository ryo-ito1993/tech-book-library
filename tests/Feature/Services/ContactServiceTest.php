<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Database\Eloquent\Collection;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Contact::create(['name' => 'Yamada Taro', 'email' => 'test1@example.com', 'message' => 'Hello', 'status' => 1]);
        Contact::create(['name' => 'Tanaka Taro', 'email' => 'test2@example.com', 'message' => 'Hi', 'status' => 2]);
    }

    public function testSearchByName(): void
    {
        /** @var Collection|Contact[] $result */
        $result = ContactService::search(['contactName' => 'Yamada'])->get();
        $this->assertCount(1, $result);
        $this->assertSame('Yamada Taro', $result->first()->name);
    }

    public function testSearchByEmail(): void
    {
        /** @var Collection|Contact[] $result */
        $result = ContactService::search(['email' => 'test1'])->get();
        $this->assertCount(1, $result);
        $this->assertSame('test1@example.com', $result->first()->email);
    }

    public function testSearchByStatus(): void
    {
        /** @var Collection|Contact[] $result */
        $result = ContactService::search(['status' => 1])->get();
        $this->assertCount(1, $result);
        $this->assertSame(1, $result->first()->status);
    }

    public function testSearchByMultiple(): void
    {
        /** @var Collection|Contact[] $result */
        $result = ContactService::search(['contactName' => 'Taro', 'email' => 'test2', 'status' => 2])->get();
        $this->assertCount(1, $result);
        $this->assertSame('Tanaka Taro', $result->first()->name);
        $this->assertSame('test2@example.com', $result->first()->email);
        $this->assertSame(2, $result->first()->status);
    }
}
