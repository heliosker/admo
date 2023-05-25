<?php namespace Tests\Repositories;

use App\Models\AlarmLogs;
use App\Repositories\AlarmLogsRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Tests\ApiTestTrait;

class AlarmLogsRepositoryTest extends TestCase
{
    use ApiTestTrait, DatabaseTransactions;

    /**
     * @var AlarmLogsRepository
     */
    protected $alarmLogsRepo;

    public function setUp() : void
    {
        parent::setUp();
        $this->alarmLogsRepo = \App::make(AlarmLogsRepository::class);
    }

    /**
     * @test create
     */
    public function test_create_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->make()->toArray();

        $createdAlarmLogs = $this->alarmLogsRepo->create($alarmLogs);

        $createdAlarmLogs = $createdAlarmLogs->toArray();
        $this->assertArrayHasKey('id', $createdAlarmLogs);
        $this->assertNotNull($createdAlarmLogs['id'], 'Created AlarmLogs must have id specified');
        $this->assertNotNull(AlarmLogs::find($createdAlarmLogs['id']), 'AlarmLogs with given id must be in DB');
        $this->assertModelData($alarmLogs, $createdAlarmLogs);
    }

    /**
     * @test read
     */
    public function test_read_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->create();

        $dbAlarmLogs = $this->alarmLogsRepo->find($alarmLogs->id);

        $dbAlarmLogs = $dbAlarmLogs->toArray();
        $this->assertModelData($alarmLogs->toArray(), $dbAlarmLogs);
    }

    /**
     * @test update
     */
    public function test_update_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->create();
        $fakeAlarmLogs = AlarmLogs::factory()->make()->toArray();

        $updatedAlarmLogs = $this->alarmLogsRepo->update($fakeAlarmLogs, $alarmLogs->id);

        $this->assertModelData($fakeAlarmLogs, $updatedAlarmLogs->toArray());
        $dbAlarmLogs = $this->alarmLogsRepo->find($alarmLogs->id);
        $this->assertModelData($fakeAlarmLogs, $dbAlarmLogs->toArray());
    }

    /**
     * @test delete
     */
    public function test_delete_alarm_logs()
    {
        $alarmLogs = AlarmLogs::factory()->create();

        $resp = $this->alarmLogsRepo->delete($alarmLogs->id);

        $this->assertTrue($resp);
        $this->assertNull(AlarmLogs::find($alarmLogs->id), 'AlarmLogs should not exist in DB');
    }
}
