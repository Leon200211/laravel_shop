<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('quantity')->default(0);
        });
    }

    public function down(): void
    {
        if (!app()->isProduction()) {
            Schema::table('products', function (Blueprint $table) {
                //
            });
        }
    }
};
