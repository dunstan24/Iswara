<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_asset_types', function (Blueprint $table) {
            $table->id('asset_type_id');
            $table->string('asset_type_name', 100);
            $table->string('asset_category', 30)->default('Bergerak');
            $table->string('asset_criticality', 30)->default('Sedang');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_asset_types');
    }
};