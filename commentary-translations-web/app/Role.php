<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The database table used by the model
     *
     * @type string
     */
    protected $table = 'roles';

    /**
     * It has many 'User'
     *
     * @access public
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->HasMany('App\User');
    }
}
