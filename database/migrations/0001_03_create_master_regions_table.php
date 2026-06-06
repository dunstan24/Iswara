<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_regions', function (Blueprint $table) {
            $table->id('region_id');
            $table->string('region_name', 100);
            $table->string('region_type', 30)->default('Banjar');
            $table->string('service_status', 30)->default('Aktif');
            $table->geography('polygon_area', 'POLYGON', 4326)->nullable();
            $table->json('polygon_geojson')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_regions');
    }
};