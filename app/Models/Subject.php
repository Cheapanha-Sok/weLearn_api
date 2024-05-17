<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'subjects';

    protected $fillable = ['category_id' , 'exam_date_id', 'pdfUrl'];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function examdate(): BelongsTo
    {
        return $this->belongsTo(ExamDate::class , 'exam_date_id');
    }
}
