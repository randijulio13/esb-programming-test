<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    function getItemById(Item $item)
    {
        return $item;
    }

    function getItems()
    {
        $items = Item::get();
        return response()->json([
            'data' => $items
        ], 200);
    }
}
