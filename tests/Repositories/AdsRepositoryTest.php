<?php namespace Tests\Repositories;

use App\Models\Ads;
use App\Repositories\AdsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AdsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AdsRepository
     */
    protected $adsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->adsRepo = \App::make(AdsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_ads()
    {
        $ads = Ads::factory()->make()->toArray();

        $createdAds = $this->adsRepo->create($ads);

        $createdAds = $createdAds->toArray();
        $this->assertArrayHasKey('id', $createdAds);
        $this->assertNotNull($createdAds['id'], 'Created Ads must have id specified');
        $this->assertNotNull(Ads::find($createdAds['id']), 'Ads with given id must be in DB');
        $this->assertModelData($ads, $createdAds);
    }

    /**
     * @test read
     */
    public function test_read_ads()
    {
        $ads = Ads::factory()->create();

        $dbAds = $this->adsRepo->find($ads->id);

        $dbAds = $dbAds->toArray();
        $this->assertModelData($ads->toArray(), $dbAds);
    }

    /**
     * @test update
     */
    public function test_update_ads()
    {
        $ads = Ads::factory()->create();
        $fakeAds = Ads::factory()->make()->toArray();

        $updatedAds = $this->adsRepo->update($fakeAds, $ads->id);

        $this->assertModelData($fakeAds, $updatedAds->toArray());
        $dbAds = $this->adsRepo->find($ads->id);
        $this->assertModelData($fakeAds, $dbAds->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_ads()
    {
        $ads = Ads::factory()->create();

        $resp = $this->adsRepo->delete($ads->id);

        $this->assertTrue($resp);
        $this->assertNull(Ads::find($ads->id), 'Ads should not exist in DB');
    }
}
