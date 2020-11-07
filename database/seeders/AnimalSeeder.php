<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AnimalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('animals')
            ->whereNotNull('age')
            ->update(['date_of_birth' => DB::raw('DATE_SUB(CURDATE(), INTERVAL `age` YEAR)')]);
    }
}
