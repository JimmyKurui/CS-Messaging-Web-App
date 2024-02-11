<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->string('body');
            $table->dateTime('timestamp')->default(Carbon::now());
            $table->string('media')->nullable();

            $table->unsignedBigInteger('agent_id')->index();
            $table->unsignedBigInteger('ticket_id')->index();

            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('cascade');
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
        Schema::dropIfExists('ticket_messages');
    }
}
