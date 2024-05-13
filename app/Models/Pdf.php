<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pdf extends Model
{
    /** The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pdfs';

    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function examdate(): BelongsTo
    {
        return $this->belongsTo(ExamDate::class, 'examdate_id');
    }
}
