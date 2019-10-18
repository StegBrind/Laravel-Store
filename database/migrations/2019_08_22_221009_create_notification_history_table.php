<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('notification_history'))
        {
            Schema::create('notification_history', function (Blueprint $table)
            {
                $table->bigIncrements('id');
                $table->string('subject', '250')->nullable();
                $table->mediumText('content_message');
                $table->boolean('done')->default(false);
                $table->timestamp('created_at')->useCurrent();
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
        Schema::dropIfExists('notification_history');
    }
}
