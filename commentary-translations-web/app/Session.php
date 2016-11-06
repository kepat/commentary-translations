<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
	/**
     * The database table used by the model
     *
     * @type string
     */
    protected $table = 'sessions';

    protected $fillable = ['token', 'user_id', 'expires_at'];

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
}
