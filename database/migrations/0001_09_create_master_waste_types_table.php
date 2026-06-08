<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_waste_types', function (Blueprint $table) {
            $table->id('waste_type_id');
            $table->string('waste_name', 100);
            $table->string('waste_category', 30)->default('Organik');
            $table->string('processing_method', 30)->default('Kompos');
            $table->string('unit', 20)->default('Kg');
            $table->decimal('default_price', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_waste_types');
    }
};