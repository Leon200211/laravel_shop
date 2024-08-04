<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->boolean('on_home_page')->default(false);
            $table->integer('sorting')->default(999);
        });
    }

    public function down(): void
    {
        if (!app()->isProduction()) {
            Schema::table('brands', function (Blueprint $table) {
                //
            });
        }
    }
};
