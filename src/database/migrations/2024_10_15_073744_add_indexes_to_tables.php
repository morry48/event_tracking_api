<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('email');
            $table->index('role_id');
        });

        Schema::table('shipments', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('internal_reference_name');
        });

        Schema::table('shipment_events', function (Blueprint $table) {
            $table->index('shipment_id');
            $table->index('event_type');
            $table->index(['shipment_id', 'event_type']);
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('permissions', function (Blueprint $table) {
            $table->index('name');
        });
    }

    public function down()
    {
        //
    }
};

