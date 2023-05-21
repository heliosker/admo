<?php namespace Tests\Repositories;

use App\Models\shops;
use App\Repositories\shopsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class shopsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var shopsRepository
     */
    protected $shopsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->shopsRepo = \App::make(shopsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_shops()
    {
        $shops = shops::factory()->make()->toArray();

        $createdshops = $this->shopsRepo->create($shops);

        $createdshops = $createdshops->toArray();
        $this->assertArrayHasKey('id', $createdshops);
        $this->assertNotNull($createdshops['id'], 'Created shops must have id specified');
        $this->assertNotNull(shops::find($createdshops['id']), 'shops with given id must be in DB');
        $this->assertModelData($shops, $createdshops);
    }

    /**
     * @test read
     */
    public function test_read_shops()
    {
        $shops = shops::factory()->create();

        $dbshops = $this->shopsRepo->find($shops->id);

        $dbshops = $dbshops->toArray();
        $this->assertModelData($shops->toArray(), $dbshops);
    }

    /**
     * @test update
     */
    public function test_update_shops()
    {
        $shops = shops::factory()->create();
        $fakeshops = shops::factory()->make()->toArray();

        $updatedshops = $this->shopsRepo->update($fakeshops, $shops->id);

        $this->assertModelData($fakeshops, $updatedshops->toArray());
        $dbshops = $this->shopsRepo->find($shops->id);
        $this->assertModelData($fakeshops, $dbshops->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_shops()
    {
        $shops = shops::factory()->create();

        $resp = $this->shopsRepo->delete($shops->id);

        $this->assertTrue($resp);
        $this->assertNull(shops::find($shops->id), 'shops should not exist in DB');
    }
}
