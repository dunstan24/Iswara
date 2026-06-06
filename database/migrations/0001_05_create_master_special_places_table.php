<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_special_places', function (Blueprint $table) {
            $table->id('place_id');
            $table->string('place_name', 100);
            $table->foreignId('region_id')->nullable()->constrained('master_regions', 'region_id')->nullOnDelete();
            $table->string('place_type', 30)->default('Lainnya');
            $table->string('service_category', 50)->default('Institusi');
            $table->string('service_status', 30)->default('Aktif');
            $table->geography('location', 'POINT', 4326)->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_special_places');
    }
};