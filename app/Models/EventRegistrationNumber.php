<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class EventRegistrationNumber.
 *
 * @property int $id
 * @property int $event_id
 * @property int $master_match_type_id
 * @property int $master_match_category_id
 * @property int|null $order_number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Event $event
 * @property MasterMatchCategory $masterMatchCategory
 * @property MasterMatchType $masterMatchType
 */
class EventRegistrationNumber extends Model
{
    protected $table = 'event_registration_numbers';

    public static $snakeAttributes = false;

    protected $casts = [
        'event_id' => 'int',
        'master_match_type_id' => 'int',
        'master_match_category_id' => 'int',
        'order_number' => 'int',
    ];

    protected $fillable = [
        'event_id',
        'master_match_type_id',
        'master_match_category_id',
        'order_number',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function masterMatchCategory()
    {
        return $this->belongsTo(MasterMatchCategory::class);
    }

    public function masterMatchType()
    {
        return $this->belongsTo(MasterMatchType::class);
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class, 'event_id', 'event_id')
            ->where('master_match_category_id', $this->master_match_category_id)
            ->whereHas('types', function ($query) {
                $query->where('master_match_type_id', $this->master_match_type_id);
            });
    }
}
