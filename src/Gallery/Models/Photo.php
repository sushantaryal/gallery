<?php

namespace Taggers\Gallery\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'gallery_id', 'original_name', 'filename',
    ];

    /*****************/
    /* Relationships */
    /*****************/
    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }
}
