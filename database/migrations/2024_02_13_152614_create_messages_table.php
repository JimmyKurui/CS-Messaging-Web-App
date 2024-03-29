<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    
    public function up()
    {
            Schema::create('messages', function (Blueprint $table) {
                $table->id();
                $table->dateTime('timestamp')->default(Carbon::now());
                $table->text('body');
                $table->unsignedBigInteger('user_id')->index();
                $table->unsignedBigInteger('ticket_id')->index()->nullable();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('no action');
                $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
