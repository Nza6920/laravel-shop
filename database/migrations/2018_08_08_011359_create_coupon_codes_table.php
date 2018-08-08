<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponCodesTable extends Migration
{
    public function up()
    {
        Schema::create('coupon_codes', function (Blueprint $table) {
            $table->increments('id')->comment('自增主键');
            $table->string('name')->comment('姓名');
            $table->string('code')->unique()->comment('优惠码');
            $table->string('type')->comment('优惠卷类型');
            $table->decimal('value')->comment('折扣值');
            $table->unsignedInteger('total')->comment('全站可兑换数量');
            $table->unsignedInteger('used')->default(0)->comment('已兑换数量');
            $table->decimal('min_amount',10,2)->comment('使用优惠卷的最低限制');
            $table->datetime('not_before')->nullable()->comment('在这个时间之前不可用');
            $table->datetime('not_after')->nullable()->comment('在这个时间之后不可用');
            $table->boolean('enabled')->comment('优惠券是否生效');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coupon_codes');
    }
}
