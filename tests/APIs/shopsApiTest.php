<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\shops;

class shopsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_shops()
    {
        $shops = shops::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/shops', $shops
        );

        $this->assertApiResponse($shops);
    }

    /**
     * @test
     */
    public function test_read_shops()
    {
        $shops = shops::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/shops/'.$shops->id
        );

        $this->assertApiResponse($shops->toArray());
    }

    /**
     * @test
     */
    public function test_update_shops()
    {
        $shops = shops::factory()->create();
        $editedshops = shops::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/shops/'.$shops->id,
            $editedshops
        );

        $this->assertApiResponse($editedshops);
    }

    /**
     * @test
     */
    public function test_delete_shops()
    {
        $shops = shops::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/shops/'.$shops->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/shops/'.$shops->id
        );

        $this->response->assertStatus(404);
    }
}
