<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class UserQuestion extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_question';
    protected $fillable = ['user_id', 'question_id'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
