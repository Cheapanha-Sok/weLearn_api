<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rank extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ranks';
    protected $fillable = [
        'point',
        'user_id',
        'category_id',
        'level_id'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function user() : BelongsTo{
        return $this->belongsTo(User::class)->withDefault();
    }

}
