<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *  upمتود  
     *شامل تمام تغییرات یا ستون های جدیدی است که به محض اجرا کردن مایگریشین روی دیتابیس اعمال میشود. 
     */
    public function up()
    {
        Schema::create('paymants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->enum('geteway',['zarinpal', 'id_pay']);
            $table->unsignedBigInteger('res_id');
            $table->unsignedBigInteger('ref_id');
            $table->enum('status', ['paid', 'unpaid']);
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paymants');
    }
}
