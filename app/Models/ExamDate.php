<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExamDate extends Model
{
   /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'exam_dates';
    protected $fillable = ['name'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function subjects():HasMany{
        return $this->hasMany(Subject::class);
    }
}
