<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'ticker',
        'name',
        'currency',
    ];

    /**
     * Get stock price history
     * @return \App\StockPriceHistory
     */
    public function history()
    {
        return $this->hasMany(StockPriceHistory::class);
    }
}
