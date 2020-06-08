<?php namespace Xitara\Core\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('xitara_core_configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('xitara_core_configs');
    }
}
