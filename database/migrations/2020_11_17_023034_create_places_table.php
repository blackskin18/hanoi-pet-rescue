<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


class CreatePlacesTable extends Migration
{
    const HOSPITAL = 1;
    const COMMON_HOME = 2;
    const FOSTER = 3;
    const OWNER = 4;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('places', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable()->default('');
            $table->string('address')->nullable()->default('');
            $table->string('note')->nullable()->default('');
            $table->string('director_name')->nullable()->default('');
            $table->string('director_phone')->nullable()->default('');
            $table->tinyInteger('type', 4);
            $table->integer('parent_id')->nullable()->default(null);
            $table->timestamps();
        });

        $hospitals = DB::table('hospitals')->get();
        foreach ($hospitals as $hospital) {
            DB::table('places')->insert([
                'type' => self::HOSPITAL,
                'phone' => $hospital->phone,
                'address' => $hospital->address,
                'note' => $hospital->note,
                'name' => $hospital->name,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('places');
    }
}
