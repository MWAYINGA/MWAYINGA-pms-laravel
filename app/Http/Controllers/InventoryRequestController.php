<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\InventoryRequest;
use App\Models\InventoryRequestItem;
use App\Models\InventoryRequestNumber;
use App\Models\InventoryStore;
use App\Models\InventoryVoucherStockRequestItem;
use App\Models\InventoryItemBatch;
use App\Models\InventoryStockOnHand;
use App\Models\InventoryStockOnHandByBatch;
use App\Models\InventoryTransaction;
use App\Models\InvTransactionVoucherRequestItem;
use App\Models\ItemUnits;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PhpParser\Node\Stmt\TryCatch;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class InventoryRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $sourceStore = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
        $viewRequest="View Requests";
        $title="View Requests";
        $users = User::get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        $stores=InventoryStore::where('retired',0)->get();
        $requests = DB::table('inventory_requests as ir')
        ->Join('inventory_request_numbers as irn', 'irn.request', 'ir.request_id')
        ->Join('inventory_stores as iss', 'iss.store_id', 'ir.destination_store')
        ->select('ir.*','irn.source as source','irn.value as requestNumber','iss.name as storeName')
        ->where('irn.source','=',1)
        ->where('ir.voided',0)
        ->where('ir.source_store',$sourceStore->store_id)
        ->get();
        $requestItems=DB::table('inventory_request_items as iri')
        ->join('inventory_items as ii','ii.inv_item_id','iri.item')
        ->join('item_units as iu','iu.unit_id','iri.units')
        ->select('iri.*','ii.name as itemName','iu.name as uOM')
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
        return view('inventory.requisition',compact(
            'requests','viewRequest','title','items','units','requestItems','stores','users','requestStatus'
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
        $title = "Create Request";
        $createRequest="Create Request";
        $users = User::get();
        $units = ItemUnits::where('voided','=',0)->get();
        $stores=InventoryStore::where('retired',0)->get();
        $items = InventoryItem::where('voided','=',0)->get();
        return view('inventory.requisition',compact(
            'createRequest','title','items','units','stores','users'
        ));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createRequestNumber(){
        $requestNumber=InventoryRequestNumber::where('source',1)->get();
        if (!$requestNumber->isEmpty()) {
            # code...
            $requestNumber = $requestNumber->last();
            $values = explode("/",$requestNumber->value);
            // $values[0]= $values[0] =='INV.'.Carbon::now()->format('Ym') ? $values[0] :
                if ($values[0] =='RQ.'.Carbon::now()->format('ym')) {
                    # code...
                    if ($values[2] >= 999) {
                        $new_middle_number=sprintf("%'03d", ($values[1]+1));
                        $new_last_number=substr(sprintf("%'03d", ($values[2]+2)),-3);
                        $new_first_string=$values[0];
                        $request_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }else{
                        $new_middle_number=sprintf("%'03d", ($values[1]));
                        $new_last_number=sprintf("%'03d", ($values[2]+1));
                        $new_first_string=$values[0];
                        $request_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }
                }else {
                    # code...
                    $values[0] =='RQ.'.Carbon::now()->format('ym');
                    $new_middle_number=sprintf("%'03d", (1));
                    $new_last_number=sprintf("%'03d", (1));
                    $new_first_string=$values[0];
                    $request_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                }
        } else {
            # code...
            $new_middle_number=sprintf("%'03d", (1));
            $new_last_number=sprintf("%'03d", (1));
            $new_first_string='RQ.'.Carbon::now()->format('ym');
            $request_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
        }
        
        return $request_number;
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
            $uuid= (string)Str::uuid();
            $destination_store =$request->store;
            $items=$request->item;
            $qty=$request->qty;
            $requestNumber = $request->requestNumber;
            $units=$request->unit; 
            $source_store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            InventoryRequest::create([
                'destination_store'=>$destination_store,
                'source_store'=>$source_store->store_id,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]);
            $request=InventoryRequest::where('uuid',$uuid)->get()->first();
    
            foreach ($items as $k => $v) {
                # code...
                InventoryRequestItem::create([
                    'request'=>$request->request_id,
                    'item'=>$items[$k],
                    'units'=>$units[$k],
                    'quantity'=>$qty[$k],
                    'equivalent_quantity'=>$qty[$k],
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
            if ($requestNumber != "" ) {
                # code...
                InventoryRequestNumber::create([
                    'request'=>$request->request_id,
                    'source'=>2,
                    'value'=>$requestNumber,
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
                InventoryRequestNumber::create([
                    'request'=>$request->request_id,
                    'source'=>1,
                    'value'=>$this->createRequestNumber(),
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
            }else {
                # code...
                InventoryRequestNumber::create([
                    'request'=>$request->request_id,
                    'source'=>1,
                    'value'=>$this->createRequestNumber(),
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
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
            return redirect()->route('requisition')->with($notification);
        }
        return redirect()->route('requisition')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryRequest  $inventoryRequest
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryRequest $inventoryRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryRequest  $inventoryRequest
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryRequest $inventoryRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryRequest  $inventoryRequest
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryRequest $inventoryRequest)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  $batch
     * @param  $store
     * @param $quantity
     * @param $operator
     * @return \Illuminate\Http\Response
     */
    public function transact($batch,$store,$quantity,$operator){
        $itemBatch=InventoryItemBatch::where('bath_reference_id',$batch)->where('voided',0)->get()->first();
        $stockOnHand=InventoryStockOnHand::where('store',$store)->where('item',$itemBatch->item);
        $stockOnBatch=InventoryStockOnHandByBatch::where('store',$store)->where('batch',$batch);
        $transactions=InventoryTransaction::where('store',$store)->where('batch',$batch);
        $uuid=(string)Str::uuid();
        if ($stockOnHand->get()->isEmpty() && $stockOnBatch->get()->isEmpty()) {
            # code...
            InventoryStockOnHand::create([
                'store'=>$store,
                'item'=>$itemBatch->item,
                'quantity'=>$quantity,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]);
            InventoryStockOnHandByBatch::create([
                'store'=>$store,
                'batch'=>$batch,
                'quantity'=>$quantity,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]);
        }else {
            # code...
            $oldStockOnBatch=$stockOnBatch->get()->first();
            $oldStockOnHand=$stockOnHand->get()->first();

            switch($operator){
                case '-':
                    $OnBatchquantity_after=$oldStockOnBatch->quantity-$quantity;
                    $OnHandquantity_after=$oldStockOnHand->quantity-$quantity;
                    break;
                case '+':
                    $OnHandquantity_after=$oldStockOnHand->quantity+$quantity;
                    $OnBatchquantity_after=$oldStockOnBatch->quantity+$quantity;
                    break;
                default:
                    break;
            }
            $stockOnHand->update([
                'quantity'=>$OnHandquantity_after
            ]);
            $stockOnBatch->update([
                'quantity'=>$OnBatchquantity_after
            ]);
        }
        if ($transactions->get()->isEmpty()) {
            # code...
            InventoryTransaction::create([
                'batch'=>$batch,
                'store'=>$store,
                'source'=>1,
                'type'=>1,
                'quantity'=>$quantity,
                'quantity_before'=>0,
                'quantity_after'=>($quantity),
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]);
        } else {
            # code...
            $transactions = $transactions->get()->last();
            switch($operator){
                case '-':
                    $type=2;
                    $quantity_after= $transactions->quantity_after-$quantity;
                    break;
                case '+':
                    $type=1;
                    $quantity_after= $transactions->quantity_after+$quantity;
                    break;
                default:
                    break;
            }
            // eval("echo $transactions->quantity_after $operator $quantity ;");
            InventoryTransaction::create([
                'batch'=>$batch,
                'store'=>$store,
                'source'=>1,
                'type'=>$type,
                'quantity'=>$quantity,
                'quantity_before'=>$transactions->quantity_after,
                'quantity_after'=>$quantity_after,
                'created_by'=>Auth::user()->id,
                'uuid'=>$uuid
            ]); 
        }
        $transaction_id=InventoryTransaction::where('uuid',$uuid)->get()->first()->transaction_id;
        return $transaction_id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryVoucherStockRequestItem  $inventoryVoucherStockRequestItem
     * @return \Illuminate\Http\Response
     */
    public function approval(Request $request){
        try {
            //code...
            DB::beginTransaction();
            $approval=$request->approval;
            $requestingStore = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            foreach ($approval as $requestItem => $value) {
                # code...
            //    $voucherItems= InventoryVoucherStockRequestItem::where('request_item',$requestItem)->get();
                $voucherItems = DB::table('inventory_voucher_stock_request_items as ivsri')
                ->join('inventory_voucher_stock_requests as ivsr','ivsri.voucher','ivsr.voucher')
                ->join('inventory_requests as ir','ir.request_id','ivsr.request')
                ->select('ivsri.*','ir.source_store as source_store','ir.destination_store as destination_store','ivsr.request as request')
                ->where('ivsri.request_item',$requestItem)
                ->where('ir.source_store',$requestingStore->store_id)
                ->where('ir.voided',0)
                ->where('ir.completed',0)->get();
                if ($voucherItems->isEmpty()) {
                    # code...
                    $notification=array(
                        'message'=>"request With Store Location Not Found",
                        'alert-type'=>'danger',
                    );
                    return redirect()->route('requisition')->with($notification);
                } else {
                    # code...
                    foreach ($voucherItems as $voucherItem) {
                        # code...
                       $inventoryVoucherStockRequestItem= InventoryVoucherStockRequestItem::where('request_item','=',$voucherItem->request_item);
                       if ($value == "Accepted") {
                           # code...
                           $inventoryVoucherStockRequestItem->update([
                            'accepted'=>1,
                            'accepted_by'=>Auth::user()->id,
                            'date_accepted'=>Carbon::now()
                       ]);
                       } else {
                           # code...
                           $inventoryVoucherStockRequestItem->update([
                            'rejected'=>1,
                            'rejected_by'=>Auth::user()->id,
                            'date_rejected'=>Carbon::now()
                       ]);
                       }
                       $voucher_item_id=$voucherItem->voucher_item_id;
                       $destinationStore=$voucherItems->first()->destination_store;
                       $sourceStore=$voucherItems->first()->source_store;
    
    
                       $ftransactions=$this->transact($voucherItem->batch,$sourceStore,$voucherItem->quantity,"+");
                       InvTransactionVoucherRequestItem::create([
                            'transaction'=>$ftransactions,
                            'voucher_item'=>$voucher_item_id,
                            'created_by'=>Auth::user()->id,
                            'uuid'=>(string)Str::uuid()
                       ]);
                       $Stransactions=$this->transact($voucherItem->batch,$destinationStore,$voucherItem->quantity,"-");
                       InvTransactionVoucherRequestItem::create([
                            'transaction'=>$Stransactions,
                            'voucher_item'=>$voucher_item_id,
                            'created_by'=>Auth::user()->id,
                            'uuid'=>(string)Str::uuid()
                       ]);
                    }
                    $inventoryRequestItem =InventoryRequestItem::where('request_item_id',$voucherItem->request_item);
                    $requestId=$voucherItems->first()->request;
                    $inventoryRequest=InventoryRequest::where('request_id',$requestId);
                    if ($value == "Accepted") {
                        # code...
                        $inventoryRequest->update([
                            'completed'=>1,
                            'completed_by'=>Auth::user()->id,
                        ]);
                        $inventoryRequestItem->update([
                            'completed'=>1,
                            'completed_by'=>Auth::user()->id,
                        ]);
                    } else {
                        # code...
                        $inventoryRequestItem->update([
                            'rejected'=>1,
                            'rejected_by'=>Auth::user()->id,
                            'date_rejected'=>Carbon::now()
                        ]);
                    }
                    // $voucherId=$voucherItems->first()->voucher;
                }
            }
            DB::commit();
            $notification=array(
                'message'=>"Request Approved Successfully",
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('requisition')->with($notification);
        }
        return redirect()->route('requisition')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryRequest  $inventoryRequest
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryRequest $inventoryRequest)
    {
        //
    }
}
