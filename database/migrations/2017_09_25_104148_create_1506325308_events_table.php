<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1506325308EventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('events')) {
            Schema::create('events', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');

                $table->string('venue_name')->nullable();
                $table->string('venue_address')->nullable();
                $table->string('description')->nullable();

                $table->unsignedInteger('total_guest')->default(0);
                $table->unsignedInteger('total_invited')->default(0);
                $table->unsignedInteger('total_accepted')->default(0);
                $table->unsignedInteger('total_rejected')->default(0);

                $table->double('total_cost',14,2)->default(0);
                $table->date('event_date')->nullable();
                
                $table->timestamps();
                $table->softDeletes();

                $table->index(['deleted_at']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
