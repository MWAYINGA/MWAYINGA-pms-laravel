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
        $binCardSearch="binCardSearch";
        $items=InventoryItem::where('voided','=',0)->get();
        $stores=InventoryStore::where('retired','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        return view('inventory.binCard',compact(
            'title','items','units','stores','binCardSearch'
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
        $binCardSearch="binCardSearch";
        $itemUuid=$request->itemUuid;
        $storeUuid=$request->storeUuid;
        $monthYear=$request->monthYear;
        if ($storeUuid == "") {
            # code...
            $notification=array(
                'message'=>"Please Select the store",
                'alert-type'=>'danger',
            );
            if (session('storeUuid') == "") {
                # code...
                return redirect()->back()->with($notification);
            } else {
                # code...
                $store=InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            }   
        }else {
            # code...
            $store=InventoryStore::where('uuid','=',$storeUuid)->get()->first();
        }
        
        $binCard="Bin Card";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $stores=InventoryStore::where('retired','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $voucher=DB::select('SELECT it.date_created as date_created,it.quantity_after as quantityBalance,it.quantity as quantityIssued, it.quantity as quantityReceived,iib.batch_no as batchNo,iib.expire_date as expireDate,sources.name , ivn.value as voucherNumber,CONCAT(is1.name," (RCV)","/",is2.name," (ISR)") as fromOrTo, u.name as creator FROM inventory_transactions it
                                    inner join users u on  u.id = it.created_by
                                    inner join inventory_transaction_sources sources on sources.source_id = it.source
                                    inner join inventory_item_batches iib on iib.bath_reference_id = it.batch
                                    inner join inventory_items ii on ii.inv_item_id = iib.item
                                    inner join inv_transaction_voucher_request_items itvri on itvri.transaction = it.transaction_id
                                    inner join inventory_voucher_stock_request_items ivsri on ivsri.voucher_item_id = itvri.voucher_item
                                    inner join inventory_voucher_numbers ivn on ivn.voucher = ivsri.voucher
                                    inner join inventory_voucher_stock_requests ivsr on ivsr.voucher = ivsri.voucher
                                    inner join inventory_requests ir on ir.request_id = ivsr.request
                                    inner join inventory_stores is1 on is1.store_id = ir.source_store
                                    inner join inventory_stores is2 on is2.store_id = ir.destination_store
                                    where ii.uuid = ? and it.store = ? and (DATE_FORMAT(it.date_created, "%Y-%m") = ?)', 
                                    [$itemUuid,$store->store_id,$monthYear]
                                );
        // $binItems = DB::table('inventory_transactions as it')
        //                 ->join('users as u','u.id','it.created_by')
        //                 ->join('inventory_transaction_sources as sources','sources.source_id','it.source')
        //                 ->join('inventory_item_batches as iib','iib.bath_reference_id','it.batch')
        //                 ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        //                 ->join('inv_transaction_voucher_request_items as itvri','itvri.transaction','it.transaction_id')
        //                 ->join('inventory_voucher_stock_request_items as ivsri','ivsri.voucher_item_id','itvri.voucher_item')
        //                 ->join('inventory_voucher_numbers as ivn','ivn.voucher','ivsri.voucher')
        //                 ->join('inventory_voucher_stock_requests as ivsr','ivsr.voucher','ivsri.voucher')
        //                 ->join('inventory_requests as ir','ir.request_id','ivsr.request')
        //                 ->join('inventory_stores as is1','is1.store_id','ir.source_store')
        //                 ->join('inventory_stores as is2','is2.store_id','ir.destination_store')
        //                 ->where('ii.uuid','=',"$itemUuid")
        //                 ->where('it.store',$store->store_id)
        //                 ->where(DB::raw("(DATE_FORMAT(it.date_created, '%Y-%m')"),$monthYear)
        //                 ->select('it.date_created as date_created','it.quantity_after as quantityBalance','it.quantity as quantityIssued', 'it.quantity as quantityReceived','iib.batch_no as batchNo','iib.expire_date as expireDate','sources.name' , 'ivn.value as voucherNumber',
        //                 DB::raw('CONCAT(is1.name," (RCV)","/",is2.name," (ISR)") as fromOrTo'), 'u.name as creator')
        //                 ->get();

        // $dispense=DB::select('SELECT it.date_created as date_created,it.quantity_after as quantityBalance,it.quantity as quantityIssued, "" as quantityReceived,iib.batch_no as batchNo,iib.expire_date as expireDate,sources.name , "" as voucherNumber,CONCAT(sources.name) as fromOrTo, u.name as creator FROM inventory_transactions it
        //                         inner join users u on  u.id = it.created_by
        //                         inner join inventory_transaction_sources sources on sources.source_id = it.source
        //                         inner join inventory_item_batches iib on iib.bath_reference_id = it.batch
        //                         inner join inventory_items ii on ii.inv_item_id = iib.item
        //                         inner join inventory_transaction_dispenses itd on itd.transaction = it.transaction_id
        //                         where ii.uuid = ? and it.store = ? and (DATE_FORMAT(it.date_created, "%Y-%m") = ?)',
        //                         [$itemUuid,$store->store_id,$monthYear]
        //                     );
        $binItems=DB::select('SELECT * FROM ((SELECT it.date_created as date_created,it.quantity_after as quantityBalance,it.quantity as quantityIssued, it.quantity as quantityReceived,iib.batch_no as batchNo,iib.expire_date as expireDate,sources.name , ivn.value as voucherNumber,CONCAT(is1.name," (RCV)","/",is2.name," (ISR)") as fromOrTo, u.name as creator FROM inventory_transactions it
                                inner join users u on  u.id = it.created_by
                                inner join inventory_transaction_sources sources on sources.source_id = it.source
                                inner join inventory_item_batches iib on iib.bath_reference_id = it.batch
                                inner join inventory_items ii on ii.inv_item_id = iib.item
                                inner join inv_transaction_voucher_request_items itvri on itvri.transaction = it.transaction_id
                                inner join inventory_voucher_stock_request_items ivsri on ivsri.voucher_item_id = itvri.voucher_item
                                inner join inventory_voucher_numbers ivn on ivn.voucher = ivsri.voucher
                                inner join inventory_voucher_stock_requests ivsr on ivsr.voucher = ivsri.voucher
                                inner join inventory_requests ir on ir.request_id = ivsr.request
                                inner join inventory_stores is1 on is1.store_id = ir.source_store
                                inner join inventory_stores is2 on is2.store_id = ir.destination_store
                                where ii.uuid = ? and it.store = ? and (DATE_FORMAT(it.date_created, "%Y-%m") = ?))
                                UNION ALL
                                SELECT it.date_created as date_created,it.quantity_after as quantityBalance,it.quantity as quantityIssued, "" as quantityReceived,iib.batch_no as batchNo,iib.expire_date as expireDate,sources.name , "" as voucherNumber,CONCAT(sources.name) as fromOrTo, u.name as creator FROM inventory_transactions it
                                inner join users u on  u.id = it.created_by
                                inner join inventory_transaction_sources sources on sources.source_id = it.source
                                inner join inventory_item_batches iib on iib.bath_reference_id = it.batch
                                inner join inventory_items ii on ii.inv_item_id = iib.item
                                inner join inventory_transaction_dispenses itd on itd.transaction = it.transaction_id
                                where ii.uuid = ? and it.store = ? and (DATE_FORMAT(it.date_created, "%Y-%m") = ?)
                                )dd order by dd.date_created asc', 
                                [$itemUuid,$store->store_id,$monthYear,$itemUuid,$store->store_id,$monthYear]
                            );
        // $dispense = DB::table('inventory_transactions as it')
        //                     ->join('users as u','u.id','it.created_by')
        //                     ->join('inventory_transaction_sources as sources','sources.source_id ', 'it.source')
        //                     ->join('inventory_item_batches as iib','iib.bath_reference_id','it.batch')
        //                     ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        //                     ->join('inventory_transaction_dispenses as itd','itd.transaction','it.transaction_id')
        //                     ->where('ii.uuid','=',$itemUuid)
        //                     ->where('it.store',$store->store_id)
        //                     ->where(DB::raw("(DATE_FORMAT(it.date_created, '%Y-%m')"),$monthYear)
        //                     ->select('it.date_created as date_created','it.quantity_after as quantityBalance','it.quantity as quantityIssued', '"" as quantityReceived','iib.batch_no as batchNo','iib.expire_date as expireDate','sources.name' , '"" as voucherNumber','CONCAT(sources.name) as fromOrTo', 'u.name as creator')
        //                     ->get();
        // $binItems=usort(array_merge($voucher,$dispense),'date_created');
                                // dd($binItems);
        return view('inventory.binCard',compact(
            'title','items','units','users','binCard','stores','binItems',
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
