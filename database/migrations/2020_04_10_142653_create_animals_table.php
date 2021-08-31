<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnimalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('scientific_name', 100);
            $table->string('local_name', 100);
            $table->string('label_name', 20);
            $table->decimal('body_height');
            $table->decimal('body_length_1');
            $table->decimal('body_length_2');
            $table->decimal('body_tail')->nullable();
            $table->decimal('body_weight');
            $table->longText('description');
            $table->text('habitat');
            $table->unsignedBigInteger('weight_unit_id')->index();
            $table->unsignedBigInteger('conservation_id')->index()->nullable();
            $table->unsignedBigInteger('cites_id')->index()->nullable();
            $table->unsignedBigInteger('redlist_id')->index()->nullable();
            $table->unsignedBigInteger('kingdom_id')->index()->nullable();
            $table->unsignedBigInteger('phylum_id')->index()->nullable();
            $table->unsignedBigInteger('class_id')->index()->nullable();
            $table->unsignedBigInteger('ordo_id')->index()->nullable();
            $table->unsignedBigInteger('family_id')->index()->nullable();
            $table->unsignedBigInteger('genus_id')->index()->nullable();
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animals');
    }
}
