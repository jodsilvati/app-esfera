<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Phone extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'description',
        'phone',
    ];

    /**
     * @return BelongsTo
     */
    public function phones(): BelongsTo
    {
        return $this->belongsTo(Phone::class);
    }
}
