<?php

use Illuminate\Database\Migrations\Migration;

class AlterEntitiesAdditionalFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::table('entities', function($table)
        {
            $table->integer('rating_count');
            $table->float('savings');
            $table->text('url')->nullable();
            $table->text('print_override')->nullable();
            $table->text('secondary_type');
        });
    }

    /**
     * Revert the changes to the database.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entities', function($table)
        {
            $table->dropColumn('rating_count');
            $table->dropColumn('savings');
            $table->dropColumn('url');
            $table->dropColumn('print_override');
            $table->dropColumn('secondary_type');
        });
    }

}