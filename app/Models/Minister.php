<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Minister extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }
    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }
    public function party()
    {
        return $this->belongsTo(Party::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
}
