<?php namespace Xitara\Core\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCustomMenusTable extends Migration
{
    public function up()
    {
        Schema::create('xitara_core_custommenus', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 20)->nullable();
            $table->string('slug', 30)->nullable();
            $table->text('links')->nullable();
            $table->boolean('is_submenu')->default(0);
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('xitara_core_linklists');
    }
}
