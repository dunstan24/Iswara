<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_roads', function (Blueprint $table) {
            $table->id('road_id');
            $table->string('road_name', 100);
            $table->foreignId('region_id')->nullable()->constrained('master_regions', 'region_id')->nullOnDelete();
            $table->string('road_type', 30)->default('Jalan');
            $table->string('access_type', 30)->default('Mobil');
            $table->string('road_surface', 30)->default('Aspal');
            $table->string('road_condition', 30)->default('Baik');
            $table->string('service_status', 30)->default('Aktif');
            $table->geography('route_line', 'LINESTRING', 4326)->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_roads');
    }
};