<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('company_id')->unsigned()->nullable()->default(null);
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('code');
            $table->string('symbol');
            $table->string('position');
            $table->boolean('is_deletable')->default(true);
            $table->timestamps();
        });

        // Insertar monedas por defecto
        DB::table('currencies')->insert([
            [
                'company_id'   => 1,
                'name'         => 'US dollar',
                'code'         => 'USD',
                'symbol'       => '$',
                'position'     => 'left',
                'is_deletable' => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'company_id'   => 1,
                'name'         => 'Colón Costa Rica',
                'code'         => 'CRC',
                'symbol'       => '₡',
                'position'     => 'left',
                'is_deletable' => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'company_id'   => 1,
                'name'         => 'Euro',
                'code'         => 'EUR',
                'symbol'       => '€',
                'position'     => 'left',
                'is_deletable' => false,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
