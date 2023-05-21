<?php namespace Tests\Repositories;

use App\Models\AdminUser;
use App\Repositories\AdminUserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AdminUserRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AdminUserRepository
     */
    protected $adminUserRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->adminUserRepo = \App::make(AdminUserRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_admin_user()
    {
        $adminUser = AdminUser::factory()->make()->toArray();

        $createdAdminUser = $this->adminUserRepo->create($adminUser);

        $createdAdminUser = $createdAdminUser->toArray();
        $this->assertArrayHasKey('id', $createdAdminUser);
        $this->assertNotNull($createdAdminUser['id'], 'Created AdminUser must have id specified');
        $this->assertNotNull(AdminUser::find($createdAdminUser['id']), 'AdminUser with given id must be in DB');
        $this->assertModelData($adminUser, $createdAdminUser);
    }

    /**
     * @test read
     */
    public function test_read_admin_user()
    {
        $adminUser = AdminUser::factory()->create();

        $dbAdminUser = $this->adminUserRepo->find($adminUser->id);

        $dbAdminUser = $dbAdminUser->toArray();
        $this->assertModelData($adminUser->toArray(), $dbAdminUser);
    }

    /**
     * @test update
     */
    public function test_update_admin_user()
    {
        $adminUser = AdminUser::factory()->create();
        $fakeAdminUser = AdminUser::factory()->make()->toArray();

        $updatedAdminUser = $this->adminUserRepo->update($fakeAdminUser, $adminUser->id);

        $this->assertModelData($fakeAdminUser, $updatedAdminUser->toArray());
        $dbAdminUser = $this->adminUserRepo->find($adminUser->id);
        $this->assertModelData($fakeAdminUser, $dbAdminUser->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_admin_user()
    {
        $adminUser = AdminUser::factory()->create();

        $resp = $this->adminUserRepo->delete($adminUser->id);

        $this->assertTrue($resp);
        $this->assertNull(AdminUser::find($adminUser->id), 'AdminUser should not exist in DB');
    }
}
