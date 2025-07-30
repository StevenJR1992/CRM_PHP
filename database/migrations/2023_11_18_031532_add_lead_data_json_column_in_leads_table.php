<?php

use App\Classes\Common;
use App\Models\Lead;
use App\Scopes\CompanyScope;
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
            $table->longText('lead_data_json')->nullable()->default(null)->after('lead_data');
        });

        $allLeadDatas = Lead::withoutGlobalScope(CompanyScope::class)->get();

        foreach ($allLeadDatas as $allLeadData) {
            $newLeadDataJson = [];
            foreach ($allLeadData->lead_data as $allLeadDataObject) {
                $convertedKey = Common::getFieldKeyByName($allLeadDataObject['field_name']);

                $newLeadDataJson[$convertedKey] = $allLeadDataObject['field_value'];
            }

            $allLeadData->lead_data_json = $newLeadDataJson;
            $allLeadData->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn('lead_data_json');
        });
    }
};
