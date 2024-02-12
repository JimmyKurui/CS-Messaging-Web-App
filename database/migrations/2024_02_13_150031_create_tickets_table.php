<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->string('description')->nullable();
            $table->string('labels')->nullable();

            $table->unsignedBigInteger('status_id')->index()->default(1);
            $table->unsignedBigInteger('priority_id')->index()->default(1);
            $table->unsignedBigInteger('category_id')->index()->nullable();
            $table->unsignedBigInteger('agent_id')->index()->nullable();
            $table->unsignedBigInteger('user_id')->index();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');
            $table->foreign('priority_id')->references('id')->on('priorities')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
