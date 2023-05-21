<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\AdminUser;

class AdminUserApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_admin_user()
    {
        $adminUser = AdminUser::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/admin_users', $adminUser
        );

        $this->assertApiResponse($adminUser);
    }

    /**
     * @test
     */
    public function test_read_admin_user()
    {
        $adminUser = AdminUser::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/admin_users/'.$adminUser->id
        );

        $this->assertApiResponse($adminUser->toArray());
    }

    /**
     * @test
     */
    public function test_update_admin_user()
    {
        $adminUser = AdminUser::factory()->create();
        $editedAdminUser = AdminUser::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/admin_users/'.$adminUser->id,
            $editedAdminUser
        );

        $this->assertApiResponse($editedAdminUser);
    }

    /**
     * @test
     */
    public function test_delete_admin_user()
    {
        $adminUser = AdminUser::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/admin_users/'.$adminUser->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/admin_users/'.$adminUser->id
        );

        $this->response->assertStatus(404);
    }
}
