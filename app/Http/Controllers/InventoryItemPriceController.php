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
        $title= "Item Price List";
        $inventoryItemPrices = InventoryItemPrice::where('voided','=',0)->get();
        $priceTemplate = DB::table('item_price_types')
        ->leftJoin('inventory_item_prices','item_price_types.price_type_id','inventory_item_prices.price_type')
        ->leftjoin('inventory_items', 'inventory_item_prices.item', '=', 'inventory_items.inv_item_id')
        ->select('inventory_items.uuid as uuid','inventory_items.inv_item_id as itemID','inventory_items.name as itemName', 'inventory_item_prices.price as price','item_price_types.name as price_type')
        ->get();
        $users = User::get();
        $units = ItemUnits::with('user')->where('voided','=',0)->get();

        // $pivotData=DB::select('
        //     SELECT * FROM 
        //         (
        //         SELECT ipt.price_type_id,ipt.name,iip.item,iip.price, values FROM  item_price_types as ipt
        //         LEFT JOIN pharmacymslm.inventory_item_prices as iip  on iip.price_type=ipt.price_type_id
        //         ) as  source_table 
        //         pivot
        //         (
        //         MAX(values) FOR ipt.name IN ("Normal Sales")
        //         ) as pivot_table
        //         ');
        $columns = DB::select("SELECT name FROM item_price_types");
        $columns = collect($columns)->pluck('name')->toArray();

        $pivotQuery = "SELECT * FROM (SELECT ipt.price_type_id, ipt.name, iip.item, iip.price,values FROM item_price_types ipt LEFT JOIN inventory_item_prices iip on iip.price_type=ipt.price_type_id) AS source_table";
        $pivotQuery .= " PIVOT (MAX(values) FOR ipt.name IN (" . implode(", ", array_map(function($col) { return "'$col'"; }, $columns)) . ")) AS pivot_table";

        $pivotData = DB::select($pivotQuery);
        $pivotData = collect($pivotData);
        return view('inventory.price-list-items',compact(
            'inventoryItemPrices','units','users','priceTemplate','title','pivotData',
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
