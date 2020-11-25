<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies__rates';

    /**
     * Get Currency
     *
     * @return Currency
     */
    public function getCurrency()
    {
        return Currency::find($this->currency_id);
    }
}
