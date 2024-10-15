<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipment_events', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('shipment_id');
            $table->foreign('shipment_id')->references('id')->on('shipments')->onDelete('cascade');

            $table->string('event_type');
            $table->unique(['shipment_id', 'event_type']);
            $table->timestamp('estimated_started_at')->nullable();
            $table->timestamp('actual_started_at')->nullable();
            $table->timestamp('estimated_completion_at')->nullable();
            $table->timestamp('actual_completion_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipment_events');
    }
}
