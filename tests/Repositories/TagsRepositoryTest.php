<?php namespace Tests\Repositories;

use App\Models\Tags;
use App\Repositories\TagsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class TagsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var TagsRepository
     */
    protected $tagsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->tagsRepo = \App::make(TagsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_tags()
    {
        $tags = Tags::factory()->make()->toArray();

        $createdTags = $this->tagsRepo->create($tags);

        $createdTags = $createdTags->toArray();
        $this->assertArrayHasKey('id', $createdTags);
        $this->assertNotNull($createdTags['id'], 'Created Tags must have id specified');
        $this->assertNotNull(Tags::find($createdTags['id']), 'Tags with given id must be in DB');
        $this->assertModelData($tags, $createdTags);
    }

    /**
     * @test read
     */
    public function test_read_tags()
    {
        $tags = Tags::factory()->create();

        $dbTags = $this->tagsRepo->find($tags->id);

        $dbTags = $dbTags->toArray();
        $this->assertModelData($tags->toArray(), $dbTags);
    }

    /**
     * @test update
     */
    public function test_update_tags()
    {
        $tags = Tags::factory()->create();
        $fakeTags = Tags::factory()->make()->toArray();

        $updatedTags = $this->tagsRepo->update($fakeTags, $tags->id);

        $this->assertModelData($fakeTags, $updatedTags->toArray());
        $dbTags = $this->tagsRepo->find($tags->id);
        $this->assertModelData($fakeTags, $dbTags->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_tags()
    {
        $tags = Tags::factory()->create();

        $resp = $this->tagsRepo->delete($tags->id);

        $this->assertTrue($resp);
        $this->assertNull(Tags::find($tags->id), 'Tags should not exist in DB');
    }
}
