<?php

namespace App\Http\Controllers;

use App\User;
use App\Stock;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function trades(Request $request)
    {
        // get user
        $user = User::where('name', $request->user)->firstOrFail();

        // get user trades
        $trades = $user->trades()->get();

        // return view
        return view('user', ['user' => $user, 'trades' => $trades]);
    }

    public function tradesByCurrency(Request $request)
    {
        // get user
        $user = User::where('name', $request->user)->firstOrFail();

        // get currency
        $currency = $request->currency;

        // get user trades
        $trades = $user->trades();

        // get only trades in currency
        $trades = $trades->whereHas('stock', function($query) use ($currency) {
                $query->where('currency', $currency);
            })->get();

        // return view
        return view('user', ['user' => $user, 'trades' => $trades]);
    }

    public function stock(Request $request)
    {
        // get user
        $user = User::where('name', $request->user)->firstOrFail();

        $ticker = $request->stock;

        // get stock
        $stock = Stock::where('ticker', $ticker)->firstOrFail();

        // get only trades related to stock
        $trades = $user->trades()->whereHas('stock', function($query) use ($ticker) {
                $query->where('ticker', $ticker);
            })->get();

        $ticker = str_replace(':', '.', $ticker);
        $ticker_file = $ticker .'.json';

        if (!file_exists($ticker_file))
        {
            // get quote
            $url = 'https://finnhub.io/api/v1/quote?symbol='. $ticker .'&token='. env('API_KEY');

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $json = curl_exec($ch);
            curl_close($ch);

            // save quote info
            file_put_contents($ticker_file, $json);
        }
        else
        {
            // load quote
            $json = file_get_contents($ticker_file);
            $json = json_decode($json);
        }

        // return view
        return view('user', ['user' => $user, 'stock' => $stock, 'trades' => $trades, 'json' => $json]);
    }

    public function indexPriceAbove(Request $request)
    {
        // get user
        $user = User::where('name', $request->user)->firstOrFail();

        // get user trades in CHF
        $trades = $user->trades();
        $trades = $trades->where('purchase_price', '>', 50)->get();

        // return view
        return view('user', ['user' => $user, 'trades' => $trades]);
    }
}
