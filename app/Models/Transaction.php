<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $table = "transactions";
    protected $primary = "id";
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps =true;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'amount',
        "status",
        "code"
    ];

    protected $atrributes = [
        "status" => 0, 
    ];

    public function setCode(){
        $this->code = "$this->user_id"."_"."$this->campaign_id"."_"."$this->amount";
        return $this->code;

    }
    public function campaign():BelongsTo
    {
        // return $this->belongsTo(Campaign::class,"campaign_id", "id");
        return $this->belongsTo(related:Campaign::class,foreignKey: "campaign_id", ownerKey: "id");
    }

    public function user():BelongsTo
    {
        // return $this->belongsTo(Campaign::class,"campaign_id", "id");
        return $this->belongsTo(related:User::class,foreignKey: "user_id", ownerKey: "id");
    }

}
