<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('posts', 'location')) {
            Schema::table('posts', function (Blueprint $table) {
                $table->dropColumn('location');
            });
        }

        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedInteger('province_id')->nullable()->after('condition');
            $table->unsignedInteger('city_id')->nullable()->after('province_id');

            $table->foreign('province_id')->references('id')->on('iran_provinces')->nullOnDelete();
            $table->foreign('city_id')->references('id')->on('iran_cities')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropForeign(['province_id']);
            $table->dropForeign(['city_id']);
            $table->dropColumn(['province_id', 'city_id']);

            $table->string('location')->after('condition');
        });
    }
};