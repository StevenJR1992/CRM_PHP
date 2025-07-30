<?php

use App\Models\Company;
use App\Models\Lead;
use App\Models\LeadStatus;
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
        $allCompanies = Company::select('id')->get();

        foreach ($allCompanies as $allCompany) {
            $interested = new LeadStatus();
            $interested->company_id = $allCompany->id;
            $interested->name = 'Interested';
            $interested->save();

            $notInterested = new LeadStatus();
            $notInterested->company_id = $allCompany->id;
            $notInterested->name = 'Not Interested';
            $notInterested->save();

            $unreachable = new LeadStatus();
            $unreachable->company_id = $allCompany->id;
            $unreachable->name = 'Unreachable';
            $unreachable->save();
        }

        $allLeads = Lead::select('id', 'company_id', 'lead_status')
            ->withoutGlobalScope(CompanyScope::class)
            ->get();


        foreach ($allLeads as $allLead) {
            $leadStatus = $allLead->lead_status;

            $newLeadStatusName = null;
            if ($leadStatus == 'interested') {
                $newLeadStatusName = 'Interested';
            } else if ($leadStatus == 'not_interested') {
                $newLeadStatusName = 'Not Interested';
            } else if ($leadStatus == 'unreachable') {
                $newLeadStatusName = 'Unreachable';
            }

            $leadStatusId = null;
            if ($newLeadStatusName != null) {
                $statusId = LeadStatus::withoutGlobalScope(CompanyScope::class)
                    ->where('company_id', $allLead->company_id)
                    ->where('name', $newLeadStatusName)
                    ->first();

                $leadStatusId = $statusId && $statusId->id ? $statusId->id : null;
            }

            $allLead->lead_status_id = $leadStatusId;
            $allLead->save();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
