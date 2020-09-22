<?php namespace Xitara\Core\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateMenusTable extends Migration
{
    public function up()
    {
        Schema::create('xitara_core_menus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('code', 100)->nullable();
            $table->string('name', 100)->nullable();
            $table->integer('sort_order')->nullable();
            $table->unique('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('xitara_core_menus');
    }
}
