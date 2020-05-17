<?php

namespace App\Http\Controllers;

use App\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index(Request $request)
    {
        // get stock
        $stock = Stock::where('ticker', $request->stock)->firstOrFail();

        $stock->getQuote();

        // get stock history
        $history = $stock->history()->get();

        return view('stock', ['stock' => $stock, 'history' => $history]);
    }
}
