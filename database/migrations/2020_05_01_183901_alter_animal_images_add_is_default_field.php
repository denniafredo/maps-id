<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAnimalImagesAddIsDefaultField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('animal_images', function (Blueprint $table) {
            $table->boolean('is_default')->after('contributor')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('animal_images', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
}
