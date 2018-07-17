<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('translations', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('source_id')->unsigned()->nullable();
            $table->foreign('source_id')
            	  ->references('id')
	              ->on('translations')
	              ->onUpdate('restrict')
	              ->onDelete('cascade');
            $table->string('locale');
            $table->text('text');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement('ALTER TABLE translations ADD COLUMN hash_id BINARY(16) NOT NULL UNIQUE AFTER id');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
        Schema::drop('translations');
	}

}
