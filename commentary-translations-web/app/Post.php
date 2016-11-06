<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The database table used by the model
     *
     * @type string
     */
    protected $table = 'posts';

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
     * It has many 'Translation'
     *
     * @access public
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany('App\Translation');
    }
}
