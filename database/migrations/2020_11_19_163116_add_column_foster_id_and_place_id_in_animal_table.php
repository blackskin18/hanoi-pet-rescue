<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFosterIdAndPlaceIdInAnimalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animals', function (Blueprint $table) {
            //
            $table->integer('foster_id')->nullable()->default(null);
            $table->integer('place_id')->nullable()->default(null);
            $table->integer('owner_id')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal', function (Blueprint $table) {
            //
        });
    }
}
