<?php

namespace Tests\Feature\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Contact;
use App\Services\ContactService;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Contact::create(['name' => 'Yamada Taro', 'email' => 'test1@example.com', 'message' => 'Hello', 'status' => 1]);
        Contact::create(['name' => 'Tanaka Taro', 'email' => 'test2@example.com', 'message' => 'Hi', 'status' => 2]);
    }

    public function testSearchByName()
    {
        $result = ContactService::search(['contactName' => 'Yamada'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Yamada Taro', $result->first()->name);
    }

    public function testSearchByEmail()
    {
        $result = ContactService::search(['email' => 'test1'])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('test1@example.com', $result->first()->email);
    }

    public function testSearchByStatus()
    {
        $result = ContactService::search(['status' => 1])->get();
        $this->assertCount(1, $result);
        $this->assertEquals(1, $result->first()->status);
    }

    public function testSearchByMultiple()
    {
        $result = ContactService::search(['contactName' => 'Taro', 'email' => 'test2', 'status' => 2])->get();
        $this->assertCount(1, $result);
        $this->assertEquals('Tanaka Taro', $result->first()->name);
        $this->assertEquals('test2@example.com', $result->first()->email);
        $this->assertEquals(2, $result->first()->status);
    }
}
