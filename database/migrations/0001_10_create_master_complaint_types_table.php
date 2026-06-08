<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('master_complaint_types', function (Blueprint $table) {
            $table->id('complaint_type_id');
            $table->string('complaint_name', 100);
            $table->string('complaint_category', 30)->default('Pengangkutan');
            $table->string('priority_level', 30)->default('Sedang');
            $table->string('responsible_role', 30)->default('Operator');
            $table->integer('SLA_response_hour')->default(24);
            $table->integer('SLA_resolution_hour')->default(72);
            $table->boolean('escalation_required')->default(false);
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('master_complaint_types');
    }
};