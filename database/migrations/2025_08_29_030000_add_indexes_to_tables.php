<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->index('category_id');
            $table->index('is_active');
            $table->index('name');
            $table->index('price');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->index('role');
            $table->index('email');
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->index('name');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['name']);
            $table->dropIndex(['price']);
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role']);
            $table->dropIndex(['email']);
        });
        
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });
    }
};