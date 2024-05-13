<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Type extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'types';
    protected $fillable =[
        'name'
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function categories(): BelongsToMany{
        return $this->belongsToMany(Category::class);
    }


}
