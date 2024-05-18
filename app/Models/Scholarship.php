<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'scholarships';
    protected $fillable = ['description', 'post_at' , 'link'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
}
