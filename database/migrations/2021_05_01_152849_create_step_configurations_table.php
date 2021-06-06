<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStepConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_configurations', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->bigInteger('pipeline_id')->unsigned();
            $table->json('details');
            $table->timestamps();

            $table->unique(['pipeline_id', 'type']);

            $table
                ->foreign('pipeline_id')
                ->references('id')
                ->on('pipelines');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('step_configurations');
    }
}
