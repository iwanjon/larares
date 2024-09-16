<?php

namespace App\Models;

use Ramsey\Uuid\Type\Integer;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Campaign
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Exam whereUpdatedAt($value)
 * @mixin \Eloquent
 */



class Campaign extends Model
{
    use HasFactory;
    protected $table = "campaigns";
    protected $primary = "id";

    protected $keyType = 'int';

    public $incrementing = true;

    public $timestamps =true;

    // public string $name;
    // public string $short_description;
    // public string  $description;
    // public string  $perks;
    // public int $backer_count;
    // public int $goal_amount;
    // public int $current_amount;
    // public string $slug;
    // public int $user_id;

    protected $fillable = [
        'name',
        'short_description',
        'description',
        "perks",
        "goal_amount",
        "slug",
        "user_id"
    ];
    
 

    public function setSlug(int $uid){
        $this->slug = "$uid"."_"."$this->name";
        return "$uid"."_"."$this->name";

    }
    // protected $attributes ={
    //     "slug" = $this->set;
    // }
    
    public function campaign_images():HasMany{
        return $this->hasMany(related:CampaignImage::class,localKey: "id");
        // return $this->hasMany(CampaignImage::class, "campaign_id","id");

    }
    public function transactions():HasMany{
        return $this->hasMany(related:Transaction::class,localKey: "id");
        // return $this->hasMany(CampaignImage::class, "campaign_id","id");
    }

    public function user():BelongsTo
    {
        // return $this->belongsTo(Campaign::class,"campaign_id", "id");
        return $this->belongsTo(related:User::class,foreignKey: "user_id", ownerKey: "id");
    }


    public static function boot()
    {
        parent::boot();

        // static::saving(function ($model) {
            // dd($model->user);
            // if ($model->slug == null ) {
                // $uid = DB::getPdo()->lastInsertId("campaigns");
                // $model->slug =$model->setSlug($model->user_id);
            // }
        // });
    }


}
