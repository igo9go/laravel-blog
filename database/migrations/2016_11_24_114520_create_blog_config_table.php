<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateBlogConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_config', function (Blueprint $table) {
            $table->increments('conf_id');
            $table->string('conf_title', 50)->nullable()->comment('//标题');
            $table->string('conf_name', 50)->nullable()->comment('//变量名');
            $table->text('conf_content')->nullable()->comment('//变量值');
            $table->integer('conf_order')->nullable()->comment('//排序');
            $table->string('conf_tips', 255)->nullable()->comment('//描述');
            $table->string('field_type', 50)->nullable()->comment('//字段类型');
            $table->string('field_value', 255)->nullable()->comment('//类型值');

            

            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_config');
    }
}
