<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id_order');
            $table->unsignedInteger('user_idd');
            $table->foreign('user_idd')->references('id')->on('utilistaurs')->onDelete('cascade');

            $table->json('items'); 
            $table->decimal('amount', 10, 2); 
            $table->text('address');
            $table->string('status')->default('Food Processing'); 
            $table->timestamp('date')->useCurrent(); 
            $table->boolean('payment')->default(false); 
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
