<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ministers', function (Blueprint $table) {
            $table->string('image', 255)->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ministers', function (Blueprint $table) {
            if (Schema::hasColumn('ministers', 'image')) {
                $table->dropColumn('image');
            }
        });
    }
};
