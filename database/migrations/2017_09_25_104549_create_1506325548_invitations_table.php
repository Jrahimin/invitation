<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Create1506325548InvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(! Schema::hasTable('invitations')) {
            Schema::create('invitations', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('event_id')->unsigned()->nullable();
                $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');

                $table->string('name');
                $table->string('mobile_number')->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->string('relation')->nullable();
                $table->unsignedInteger('people_count')->default(1);

                $table->string('remark')->nullable();

                $table->datetime('sent_at')->nullable();
                $table->datetime('accepted_at')->nullable();
                $table->datetime('rejected_at')->nullable();

                $table->unsignedInteger('direct_contact_count')->default(0);
                $table->unsignedInteger('phone_count')->default(0);
                $table->unsignedInteger('sms_count')->default(0);
                $table->unsignedInteger('email_count')->default(0);

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
        Schema::dropIfExists('invitations');
    }
}
