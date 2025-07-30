<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use OwenIt\Auditing\Contracts\Auditable;

class Lead extends BaseModel implements Auditable
{
    use \OwenIt\Auditing\Auditable;

    use HasFactory;

    protected $table = 'leads';

    protected $default = ['xid', 'reference_number'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['company_id', 'campaign_id', 'first_action_by', 'last_action_by', 'lead_follow_up_id', 'salesman_booking_id', 'lead_status_id', 'assign_to'];

    protected $appends = ['xid', 'x_company_id', 'x_campaign_id', 'x_first_action_by', 'x_last_action_by', 'x_lead_follow_up_id', 'x_salesman_booking_id', 'x_lead_status_id', 'x_assign_to'];

    protected $filterable = ['reference_number', 'campaign_id', 'lead_status_id'];

    protected $hashableGetterFunctions = [
        'getXCompanyIdAttribute' => 'company_id',
        'getXCampaignIdAttribute' => 'campaign_id',
        'getXFirstActionByAttribute' => 'first_action_by',
        'getXLastActionByAttribute' => 'last_action_by',
        'getXLeadFollowUpIdAttribute' => 'lead_follow_up_id',
        'getXSalesmanBookingIdAttribute' => 'salesman_booking_id',
        'getXLeadStatusIdAttribute' => 'lead_status_id',
        'getXAssignToAttribute' => 'assign_to',
    ];

    protected $casts = [
        'company_id' => Hash::class . ':hash',
        'campaign_id' => Hash::class . ':hash',
        'first_action_by' => Hash::class . ':hash',
        'last_action_by' => Hash::class . ':hash',
        'lead_follow_up_id' => Hash::class . ':hash',
        'salesman_booking_id' => Hash::class . ':hash',
        'lead_status_id' => Hash::class . ':hash',
        'assign_to' => Hash::class . ':hash',
        'lead_data' => 'array',
        'lead_data_json' => 'array',
        'time_taken' => 'integer',
        'started' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function firstActioner()
    {
        return $this->belongsTo(User::class, 'first_action_by', 'id');
    }

    public function lastActioner()
    {
        return $this->belongsTo(User::class, 'last_action_by', 'id');
    }

    public function leadFollowUp()
    {
        return $this->belongsTo(LeadLog::class, 'lead_follow_up_id', 'id');
    }

    public function salesmanBooking()
    {
        return $this->belongsTo(LeadLog::class, 'salesman_booking_id', 'id');
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class);
    }

    public function uphoneCalls()
    {
        return $this->belongsTo(UphoneCalls::class, 'id', 'lead_id');
    }

    public function campaignUsers()
    {
        return $this->hasMany(CampaignUser::class, 'campaign_id', 'campaign_id');
    }

    public function assignUsers()
    {
        return $this->belongsTo(User::class, 'assign_to', 'id');
    }
}