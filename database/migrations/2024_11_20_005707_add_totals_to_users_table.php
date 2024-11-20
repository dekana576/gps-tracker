<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTotalsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('total_distance', 10, 2)->default(0)->after('email'); // Tambahkan kolom total_distance
            $table->integer('total_duration')->default(0)->after('total_distance'); // Tambahkan kolom total_duration
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('total_distance');
            $table->dropColumn('total_duration');
        });
    }
}
