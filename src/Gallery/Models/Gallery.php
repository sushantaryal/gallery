<?php

namespace Taggers\Gallery\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Gallery extends Model
{
    use Sluggable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'status',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /*****************/
    /* Relationships */
    /*****************/
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    /**********/
	/* Scopes */
	/**********/
    public function scopePublished($query)
	{
		return $query->where('status', 1);
	}

    /**********************/
	/* Additional Methods */
	/**********************/
    public function statusString()
	{
		if($this->status == 1) {
			return '<a href="'.route('galleries.updatestatus', $this->id).'"><span class="label label-success">Public</span></a>';
		} else {
			return '<a href="'.route('galleries.updatestatus', $this->id).'"><span class="label label-danger">Hidden</span></a>';
		}
	}
}
