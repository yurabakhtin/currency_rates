<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

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

    /**
     * Filter query by search keyword
     *
     * @param object $query
     * @param string $keyword search keyword
     * @param array $columns The filtered columns
     * @return $query
     */
    public function scopeSearch($query, $keyword, $columns = ['internal_id', 'number', 'code', 'name'])
    {
        if ($keyword !== '' && !empty($columns)) {
            foreach ($columns as $column) {
                $query->orWhere($column, 'like', '%' . $keyword . '%');
            }
        }

        return $query;
    }
}
