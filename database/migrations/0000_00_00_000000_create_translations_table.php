<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTranslationsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('translations', function(Blueprint $table) {
		    $table->increments('id');
		    $table->char('hash_id', 32);
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
		    $table->unique(['hash_id', 'source_id']);
		});
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
