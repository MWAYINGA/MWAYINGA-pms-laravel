<?php

namespace App\Http\Controllers;

use App\Models\InventoryItemPrice;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryItem;
use App\Models\User;
use App\Models\ItemUnits;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class InventoryItemPriceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tittle= "Item Price List";
        $inventoryItemPrices = InventoryItemPrice::where('voided','=',0)->get();
        $priceTemplate = DB::table('item_price_types')
        ->leftJoin('inventory_item_prices','item_price_types.price_type_id','inventory_item_prices.price_type')
        ->leftjoin('inventory_items', 'inventory_item_prices.item', '=', 'inventory_items.inv_item_id')
        ->select('inventory_items.*', 'inventory_item_prices.*','item_price_types.name as price_type')
        ->get()->groupBy('inv_item_id');
        $users = User::get();
        $units = ItemUnits::with('user')->where('voided','=',0)->get();
        return view('inventory.price-list-items',compact(
            'inventoryItemPrices','units','users','priceTemplate','tittle',
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryItemPrice  $inventoryItemPrice
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryItemPrice $inventoryItemPrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryItemPrice  $inventoryItemPrice
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryItemPrice $inventoryItemPrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryItemPrice  $inventoryItemPrice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryItemPrice $inventoryItemPrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryItemPrice  $inventoryItemPrice
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryItemPrice $inventoryItemPrice)
    {
        //
    }
}
