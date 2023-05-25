<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\Tags;

class TagsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_tags()
    {
        $tags = Tags::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/tags', $tags
        );

        $this->assertApiResponse($tags);
    }

    /**
     * @test
     */
    public function test_read_tags()
    {
        $tags = Tags::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/tags/'.$tags->id
        );

        $this->assertApiResponse($tags->toArray());
    }

    /**
     * @test
     */
    public function test_update_tags()
    {
        $tags = Tags::factory()->create();
        $editedTags = Tags::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/tags/'.$tags->id,
            $editedTags
        );

        $this->assertApiResponse($editedTags);
    }

    /**
     * @test
     */
    public function test_delete_tags()
    {
        $tags = Tags::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/tags/'.$tags->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/tags/'.$tags->id
        );

        $this->response->assertStatus(404);
    }
}
