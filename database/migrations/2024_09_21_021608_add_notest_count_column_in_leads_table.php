<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->integer('notes_count')->default(0)->after('lead_hash');
            $table->bigInteger('assign_to')->unsigned()->nullable()->default(null);
            $table->foreign('assign_to')->references('id')->on('users')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('notes_count');

            $table->dropForeign('leads_assign_to_foreign');
            $table->dropColumn('assign_to');
        });
    }
};
