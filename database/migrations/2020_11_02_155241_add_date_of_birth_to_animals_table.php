<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddDateOfBirthToAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            $table->date('date_of_birth')->default('2000-01-01');
        });

        DB::table('animals')
            ->whereNotNull('age')
            ->update(['date_of_birth' => DB::raw('DATE_SUB(CURDATE(), INTERVAL `age` YEAR)')]);

        DB::table('animals')
            ->update(['type' => DB::raw("(CASE
            WHEN `type`='Chó' THEN 1
            WHEN `type`='Cho' THEN 1
            WHEN `type`='chó' THEN 1
            WHEN `type`='cho' THEN 1
            WHEN `type`='Mèo' THEN 2
            WHEN `type`='Meo' THEN 2
            WHEN `type`='mèo' THEN 2
            WHEN `type`='meo' THEN 2
            ELSE 3 END)")]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animals', function (Blueprint $table) {
        });
    }
}
