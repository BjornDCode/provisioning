<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('identifier');
            $table->string('email');
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', [
                'github',
            ]);
            $table->string('token');
            $table->string('refresh_token')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
