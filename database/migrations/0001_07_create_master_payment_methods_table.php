<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_payment_methods', function (Blueprint $table) {
            $table->id('payment_method_id');
            $table->string('payment_method_name', 100);
            $table->string('payment_category', 30)->default('Tunai');
            $table->string('account_number', 50)->nullable();
            $table->string('account_name', 100)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_payment_methods');
    }
};