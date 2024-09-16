<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CampaignImage extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = "campaign_images";
    protected $primary = "id";

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps =true;

    protected $fillable=[
        "campaign_id",
        "filename",
        "is_primary"
    ];


    public function campaign():BelongsTo
    {
        // return $this->belongsTo(Campaign::class,"campaign_id", "id");
        return $this->belongsTo(related:Campaign::class,foreignKey: "campaign_id", ownerKey: "id");
    }

}
