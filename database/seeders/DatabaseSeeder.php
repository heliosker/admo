<?php

namespace Database\Seeders;

use App\Models\Shops;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        AdminUser::factory(5)->create();
        $this->call(AdminUser::class);
    }
}
