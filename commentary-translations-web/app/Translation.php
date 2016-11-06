<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    /**
     * The database table used by the model
     *
     * @type string
     */
    protected $table = 'translations';

    /**
     * The attributes that are mass assignable
     *
     * @type array
     */
    protected $fillable = [
        'title',
        'content',
        'user_id'
    ];

    /**
     * It belongs to 'User'
     *
     * @access public
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * It belongs to 'Post'
     *
     * @access public
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo('App\Post');
    }
}
