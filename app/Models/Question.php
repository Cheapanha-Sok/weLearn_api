<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'questions';
    protected $fillable = ['name', 'category_id', 'level_id', 'is_graduate'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function choices(): HasMany
    {
        return $this->hasMany(Choice::class);
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_question');
    }

}
