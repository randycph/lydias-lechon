<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SentCampaign extends Model
{
    protected $fillable = ['name', 'sender_id', 'campaign_id', 'from_name', 'from_email', 'subject', 'content'];

    public function all_subscribers()
    {
        return $this->hasMany(SentCampaignSubscriber::class);
    }

    public function group_subscribers()
    {
        return $this->all_subscribers()->whereNotNull('group_id')->get();
    }

    public function group_ids()
    {
        return array_unique($this->group_subscribers()->pluck('group_id')->toArray());
    }

    public function total_group()
    {
        return count($this->group_ids());
    }

    public function subscribers()
    {
        return $this->all_subscribers()->whereNull('group_id')->get();
    }

    public function total_subscriber()
    {
        return $this->subscribers()->count();
    }
}
