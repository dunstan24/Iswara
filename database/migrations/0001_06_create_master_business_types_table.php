<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_business_types', function (Blueprint $table) {
            $table->id('business_type_id');
            $table->string('business_type_name', 100);
            $table->string('service_category', 50)->default('Usaha Kecil');
            $table->string('recommended_collection_frequency', 30)->default('Harian');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_business_types');
    }
};