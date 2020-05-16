<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Trade extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'stock_id',
        'quantity',
        'purchase_date',
        'purchase_price',
    ];

    /**
     * Get user that owns lot
     * @return \App\User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get stock object
     * @return \App\Stock
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    /**
     * Get stock name
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->stock()->first()->name;
    }

    /**
     * Get stock currency
     * @return string
     */
    public function getCurrencyAttribute()
    {
        return $this->stock()->first()->currency;
    }

    /**
     * Get trade total value
     * @return int
     */
    public function getTotalAttribute()
    {
        return $this->quantity * $this->purchase_price;
    }
}
