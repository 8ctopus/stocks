<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StockPriceHistory extends Model
{
    public $timestamps = false;

    /**
     * Get stock that owns history
     * @return \App\Stock
     */
    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public function getQuote()
    {
        // get quote
        $url = 'https://finnhub.io/api/v1/quote?symbol='. $ticker .'&token='. env('API_KEY');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $json = curl_exec($ch);
        curl_close($ch);

        $json = json_decode($json);

    }
}
