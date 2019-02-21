<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPasswordTokenToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table(app(config('auth.providers.users.model'))->table ?: 'users', function (Blueprint $table) {
            $table->string('password_token', 100)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table(app(config('auth.providers.users.model'))->table ?: 'users', function (Blueprint $table) {
            $table->dropColumn('password_token');
        });
    }
}
