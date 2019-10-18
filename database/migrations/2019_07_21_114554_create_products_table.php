<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('products'))
        {
            Schema::create('products', function (Blueprint $table)
            {
                $table->bigIncrements('id');
                $table->string('name', 100);
                $table->mediumText('description');
                $table->string('image_paths', 250);
                $table->float('price')->default(0);
                $table->integer('category_id', false, true);
                $table->string('author', 110);
                $table->integer('author_id', false, true);
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
        Schema::dropIfExists('products');
    }
}
