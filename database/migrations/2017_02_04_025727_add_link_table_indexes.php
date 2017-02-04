<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinkTableIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table)
        {
            // Add long_url hashes
            $table->unique('short_url');
            $table->string('long_url_hash', 10)->nullable();
            $table->index('long_url_hash', 'links_long_url_index');
        });

        DB::query("UPDATE links SET long_url_hash = crc32(long_url)");
    }

    public function down()
    {
        Schema::table('links', function (Blueprint $table)
        {
            $table->longtext('long_url')->change();
            $table->dropUnique('links_short_url_unique');
            $table->dropIndex('links_long_url_index');
            $table->dropColumn('long_url_hash');
        });
    }
}
