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

class IssuingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $destinantionStore = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
        $viewRequest="View Requests";
        $title="View Requests";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $stores=InventoryStore::where('retired',0)->get();
        $requests = DB::table('inventory_requests as ir')
        ->Join('inventory_request_numbers as irn', 'irn.request', 'ir.request_id')
        ->Join('inventory_stores as iss', 'iss.store_id', 'ir.source_store')
        ->select('ir.*','irn.source as source','irn.value as requestNumber','iss.name as storeName')
        ->where('irn.source','=',1)
        ->where('ir.voided',0)
        ->where('ir.destination_store',$destinantionStore->store_id)
        ->get();
        $requestItems=DB::table('inventory_request_items as iri')
        ->join('inventory_items as ii','ii.inv_item_id','iri.item')
        ->join('item_units as iu','iu.unit_id','iri.units')
        ->select('iri.*','ii.inv_item_id as itemId','ii.name as itemName','iu.name as uOM')
        ->where('iri.voided',0)
        ->get();
        $requestStatus = DB::table('inventory_vouchers as iv')
        ->Join('inventory_voucher_stock_requests as ivsr', 'ivsr.voucher', 'iv.voucher_id')
        ->Join('inventory_voucher_stock_request_items as ivsri', 'ivsri.voucher', 'iv.voucher_id')
        ->join('inventory_voucher_numbers as ivn','ivn.voucher','iv.voucher_id')
        ->join('inventory_requests as ir','ivsr.request','ir.request_id')
        ->select('iv.*','ivsr.request as request','ivsri.request_item as requestItem','ivsri.batch as batchNo','ivsri.quantity as batchQuantity','ir.completed as completedStatus')
        ->where('ivn.source','=',1)
        ->get();
        $onHandItemBatch = DB::table('inventory_item_batches as iib')
        ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        ->join('inventory_stock_on_hand_by_batches as ishb', 'ishb.batch','iib.bath_reference_id')
        ->join('inventory_stores as iss','iss.store_id','ishb.store')
        ->select('ishb.*','iib.batch_no as batchNo','iib.item as item','iib.expire_date as expireDate','iib.bath_reference_id as batchId')
        ->where('iib.voided',0)
        ->get();
        return view('inventory.issuing',compact(
            'requests','viewRequest','title','items','units','requestItems','stores','users','requestStatus','onHandItemBatch'
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createVoucherNumber(){
        $voucherNumber=InventoryVoucherNumber::where('source',1)->get();
        if (!$voucherNumber->isEmpty()) {
            # code...
            $voucherNumber = $voucherNumber->last();
            $values = explode("/",$voucherNumber->value);
            // $values[0]= $values[0] =='INV.'.Carbon::now()->format('Ym') ? $values[0] :
                if ($values[0] =='VC.'.Carbon::now()->format('ym')) {
                    # code...
                    if ($values[2] >= 999) {
                        $new_middle_number=sprintf("%'03d", ($values[1]+1));
                        $new_last_number=substr(sprintf("%'03d", ($values[2]+2)),-3);
                        $new_first_string=$values[0];
                        $voucher_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }else{
                        $new_middle_number=sprintf("%'03d", ($values[1]));
                        $new_last_number=sprintf("%'03d", ($values[2]+1));
                        $new_first_string=$values[0];
                        $voucher_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }
                }else {
                    # code...
                    $values[0] =='VC.'.Carbon::now()->format('ym');
                    $new_middle_number=sprintf("%'03d", (1));
                    $new_last_number=sprintf("%'03d", (1));
                    $new_first_string=$values[0];
                    $voucher_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                }
        } else {
            # code...
            $new_middle_number=sprintf("%'03d", (1));
            $new_last_number=sprintf("%'03d", (1));
            $new_first_string='VC.'.Carbon::now()->format('ym');
            $voucher_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
        }
        
        return $voucher_number;
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
        try {
            //code...
            DB::beginTransaction();
            $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            $requestId=$request->requestId;
            $qtyIssued=$request->qtyIssued;
            $uuid=(string)Str::uuid();
            InventoryVoucher::create([
                'store'=>$store->store_id,
                'reference_source'=>1,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]);
            $voucherId = InventoryVoucher::where('uuid','=',$uuid)->get()->first()->voucher_id;
            InventoryVoucherNumber::create([
                'voucher'=>$voucherId,
                'source'=>1,
                'value'=>$this->createVoucherNumber(),
                'uuid'=>$uuid,
                'created_by' =>Auth::user()->id
            ]);
            InventoryVoucherStockRequest::create([
                'voucher'=>$voucherId,
                'request'=>$requestId,
                'uuid'=>$uuid,
                'created_by'=>Auth::user()->id
            ]);
            foreach ($qtyIssued as $requestItems => $value) {
                # code...
                foreach ($value as $batch => $qty) {
                    # code...
                    InventoryVoucherStockRequestItem::create([
                        'voucher'=>$voucherId,
                        'request_item'=>$requestItems,
                        'batch'=>$batch,
                        'quantity'=>$qty,
                        'uuid'=>(string)Str::uuid(),
                        'created_by'=>Auth::user()->id
                    ]);
                }
            }
            DB::commit();
            $notification=array(
                'message'=>"Request has been Saved",
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('issuing')->with($notification);
        }
        return redirect()->route('issuing')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
