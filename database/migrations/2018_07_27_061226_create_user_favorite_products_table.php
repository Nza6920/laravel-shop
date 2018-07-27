<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFavoriteProductsTable extends Migration
{
    public function up()
    {
        Schema::create('user_favorite_products', function (Blueprint $table) {
            $table->increments('id')->comment('自增主键');
            $table->unsignedInteger('user_id')->comment('用户id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('product_id')->comment('产品id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_favorite_products');
    }
}
