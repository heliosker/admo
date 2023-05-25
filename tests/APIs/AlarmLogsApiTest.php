<?php namespace Tests\APIs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;
use App\Models\AlarmLogs;

class AlarmLogsApiTest extends TestCase
{
    use ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function test_create_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->make()->toArray();

        $this->response = $this->json(
            'POST',
            '/api/alarm_logs', $alarmLogs
        );

        $this->assertApiResponse($alarmLogs);
    }

    /**
     * @test
     */
    public function test_read_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->create();

        $this->response = $this->json(
            'GET',
            '/api/alarm_logs/'.$alarmLogs->id
        );

        $this->assertApiResponse($alarmLogs->toArray());
    }

    /**
     * @test
     */
    public function test_update_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->create();
        $editedAlarmLogs = AlarmLogs::factory()->make()->toArray();

        $this->response = $this->json(
            'PUT',
            '/api/alarm_logs/'.$alarmLogs->id,
            $editedAlarmLogs
        );

        $this->assertApiResponse($editedAlarmLogs);
    }

    /**
     * @test
     */
    public function test_delete_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->create();

        $this->response = $this->json(
            'DELETE',
             '/api/alarm_logs/'.$alarmLogs->id
         );

        $this->assertApiSuccess();
        $this->response = $this->json(
            'GET',
            '/api/alarm_logs/'.$alarmLogs->id
        );

        $this->response->assertStatus(404);
    }
}
