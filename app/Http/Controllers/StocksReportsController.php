<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\InventoryRequestNumber;
use App\Models\InventoryStore;
use App\Models\InventoryVoucher;
use App\Models\InventoryVoucherNumber;
use App\Models\InventoryVoucherStockRequestItem;
use App\Models\InventoryVoucherStockRequest;
use App\Models\ItemUnits;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class StocksReportsController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */  
    public function binCard(){
        $title="Bin Card";
        $items=InventoryItem::where('voided','=',0)->get();
        $stores=InventoryStore::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        return view('inventory.binCard',compact(
            'title','items','units','stores',
        ));
    }
    /**
     * Display a listing of the resource.
     * 
     *@param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function binCardSearch(Request  $request)
    {
        //
        $title="Bin Card";
        $binCard="Bin Card";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $binItems="";
        return view('inventory.binCard',compact(
            'createInsuranceSales','title','items','units','users','binCard'
        ));

    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function ledger()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function stockStatus()
    {
        //
        $stockStatus="stockStatus";
        $title="Stock status";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
        if (InventoryStore::where('uuid','=',session('storeUuid'))->get()->isEmpty()){
            $notification=array(
                'message'=>"Please Select Store Location",
                'alert-type'=>'danger',
            );
            return redirect()->route('stockStatus')->with($notification);
        }
        $stockOnHand=DB::table('inventory_stock_on_hands as isoh')
        ->join('inventory_items as ii','ii.inv_item_id','isoh.item')
        ->join('item_units as iu','iu.unit_id','ii.units')
        ->select('isoh.*','ii.name as itemName','iu.name as uOM')
        ->where('isoh.store',$store->store_id)
        ->get();
        return view('inventory.stockStatus',compact(
            'title','items','units','users','stockOnHand','store','stockStatus'
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function detailedStockStatus()
    {
        //
        $title="Detailed Stock Status";
        $detailedStockStatus="detailedStockStatus";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
        if (InventoryStore::where('uuid','=',session('storeUuid'))->get()->isEmpty()){
            $notification=array(
                'message'=>"Please Select Store Location",
                'alert-type'=>'danger',
            );
            return redirect()->route('detailedStockStatus')->with($notification);
        }
        $stockOnHandByBatch=DB::table('inventory_stock_on_hand_by_batches as isohb')
        ->join('inventory_item_batches as iib','iib.bath_reference_id','isohb.batch')
        ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        ->join('item_units as iu','iu.unit_id','ii.units')
        ->select('isohb.*','ii.name as itemName','iu.name as uOM','iib.batch_no as batch','ii.inv_item_id as itemId')
        ->where('isohb.store',$store->store_id)
        ->orderBy('ii.inv_item_id','asc')
        ->get();
        return view('inventory.detailedStockStatus',compact(
            'stockOnHandByBatch','title','items','units','users','detailedStockStatus','store'
        ));
    }
}
