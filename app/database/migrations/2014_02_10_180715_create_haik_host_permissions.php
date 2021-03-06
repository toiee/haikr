<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHaikHostPermissions extends Migration {
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('haik_host_permissions', function($table)
        {
            $table->increments('id')->unsigned();
            $table->integer('haik_user_id')->unsigned();
            $table->string('permissions');
            $table->timestamps();
        });
    
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('haik_host_permissions');
    }
    
}
