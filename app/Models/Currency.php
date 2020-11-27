<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * @var bool Don't use default timestamp columns for this model
     */
    public $timestamps = false;

    /**
     * Get the phone record associated with the user.
     */
    public function rates()
    {
        return $this->hasMany('App\Models\CurrencyRate');
    }
}
