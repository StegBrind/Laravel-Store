<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users'))
        {
            Schema::create('users', function (Blueprint $table)
            {
                $table->bigIncrements('id');
                $table->string('login', 200)->unique();
                $table->string('password', 60);
                $table->string('email', 200)->unique();
                $table->text('name_surname');
                $table->mediumText('conversation_list');
                $table->text('remember_token')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('users');
    }
}
