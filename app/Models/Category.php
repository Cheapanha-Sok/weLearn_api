<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    protected $fillable = ['name'];


    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function types(): BelongsToMany
    {
        return $this->belongsToMany(Type::class);
    }
    public function ranks(): HasMany
    {
        return $this->hasMany(Rank::class);
    }
}
