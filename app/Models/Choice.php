<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Choice extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'choices';
    protected $fillable = ['name', 'question_id', 'is_correct'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function question():BelongsTo{
        return $this->belongsTo(Question::class);
    }
    public function types() : BelongsToMany{
        return $this->belongsToMany(Type::class);
    }
}
