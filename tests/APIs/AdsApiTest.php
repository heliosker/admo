<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Ads;

class AdsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_ads()
    {
        $ads = Ads::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/ads', $ads
        );

        $this->assertApiResponse($ads);
    }

    /**
     * @test
     */
    public function test_read_ads()
    {
        $ads = Ads::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/ads/'.$ads->id
        );

        $this->assertApiResponse($ads->toArray());
    }

    /**
     * @test
     */
    public function test_update_ads()
    {
        $ads = Ads::factory()->create();
        $editedAds = Ads::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/ads/'.$ads->id,
            $editedAds
        );

        $this->assertApiResponse($editedAds);
    }

    /**
     * @test
     */
    public function test_delete_ads()
    {
        $ads = Ads::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/ads/'.$ads->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/ads/'.$ads->id
        );

        $this->response->assertStatus(404);
    }
}
