<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Add2faColumnsToUsersTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            $table->string('two_factor_code', 10)->nullable();
            $table->dateTime('two_factor_expires_at')->nullable();
            $table->string('otp', 10)->nullable();
            $table->dateTime('otp_expires_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['two_factor_code', 'two_factor_expires_at', 'otp', 'otp_expires_at']);
        });
    }
}
