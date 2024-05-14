<?php

namespace App\Http\Controllers;

use App\Models\InventoryInvoice;
use App\Models\InventoryInvoiceItem;
use App\Models\InventoryInvoiceNumber;
use App\Models\InventoryItem;
use App\Models\InventoryItemBatch;
use App\Models\InventoryStockOnHand;
use App\Models\InventoryStockOnHandByBatch;
use App\Models\InventoryStore;
use App\Models\InventoryStoreAttribute;
use App\Models\InventorySupplier;
use App\Models\InventoryTransaction;
use App\Models\InventoryTransactionInvoiceItem;
use App\Models\User;
use Carbon\Carbon;
use App\Models\ItemUnits;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class InventoryInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $viewInvoice="View Invoices";
        $title="View Invoices";
        $users = User::get();
        $inventoryInvoice = InventoryInvoice ::where('voided','=',0)->get();
        $items=InventoryItem::where('voided','=',0)->get();
        $units=ItemUnits::where('voided','=',0)->get();
        // $invoiceItems=InventoryInvoiceItem::where('voided','=',0)->get();
        $stores=InventoryStore::where('retired',0)->get();
        $invoices = DB::table('inventory_invoices as ii')
        ->Join('inventory_invoice_numbers as iin', 'iin.invoice', 'ii.invoice_id')
        ->Join('inventory_stores as iss', 'iss.store_id', 'ii.store')
        ->join('inventory_suppliers as s','ii.supplier','s.supplier_id')
        ->select('ii.*','iin.source as source','iin.value as invoiceNumber','iss.name as storeName','s.name as supplierName')
        ->where('iin.source','=',1)
        ->get();
        $invoiceItems=DB::table('inventory_invoice_items as iii')
        ->join('inventory_item_batches as iib','iib.bath_reference_id','iii.batch')
        ->join('inventory_items as ii','ii.inv_item_id','iib.item')
        ->join('item_units as iu','iu.unit_id','ii.units')
        ->select('iii.*','iib.batch_no as batchNo','iib.expire_date as expireDate','ii.name as itemName','iu.name as uOM')
        ->where('iii.voided',0)
        ->get();
        return view('inventory.invoice',compact(
            'invoices','viewInvoice','title','inventoryInvoice','items','units','invoiceItems','stores','users'
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
        $title = "Create Invoice";
        $createInvoice="Create Invoice";
        $users = User::get();
        $units = ItemUnits::where('voided','=',0)->get();
        $items = InventoryItem::where('voided','=',0)->get();
        $suppliers= InventorySupplier::where('retired','=',0)->get();
        return view('inventory.invoice',compact(
            'title','users','units','items','createInvoice','suppliers'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function createInvoiveNumber(){
        $invoiceNumber = InventoryInvoiceNumber::where('source',1)->get();
        if (!$invoiceNumber->isEmpty()) {
            # code...
            $invoiceNumber = $invoiceNumber->last();
            $values = explode("/",$invoiceNumber->value);
            // $values[0]= $values[0] =='INV.'.Carbon::now()->format('Ym') ? $values[0] :
                if ($values[0] == "INV.".Carbon::now()->format('ym')) {
                    # code...
                    if ($values[2] >= 999) {
                        $new_middle_number=sprintf("%'03d", ($values[1]+1));
                        $new_last_number=substr(sprintf("%'03d", ($values[2]+2)),-3);
                        $new_first_string=$values[0];
                        $invoice_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }else{
                        $new_middle_number=sprintf("%'03d", ($values[1]));
                        $new_last_number=sprintf("%'03d", ($values[2]+1));
                        $new_first_string=$values[0];
                        $invoice_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                    }
                }else {
                    # code...
                    $values[0] =="INV.".Carbon::now()->format('ym');
                    $new_middle_number=sprintf("%'03d", (1));
                    $new_last_number=sprintf("%'03d", (1));
                    $new_first_string=$values[0];
                    $invoice_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
                }
        } else {
            # code...
            $new_middle_number=sprintf("%'03d", (1));
            $new_last_number=sprintf("%'03d", (1));
            $new_first_string="INV.".Carbon::now()->format('ym');
            $invoice_number=$new_first_string.'/'.$new_middle_number.'/'.$new_last_number;
        }
        
        return $invoice_number;
    }
    public function store(Request $request)
    {
        //
        try {
            //code...
            DB::beginTransaction();
            $uuid= (string)Str::uuid();
            $supplier=$request->supplier;
            $invoiceNumber=$request->invoiceNumber;
            $item=$request->item;
            $unit=$request->unit;
            $batch=$request->batch;
            $expiryDate=$request->expiryDate;
            $qty=$request->qty;
            $uprice=$request->uprice;
            $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            $storeAttributes =InventoryStoreAttribute::where('store',$store->store_id)->where('type',1)->get()->first();
            if (! $storeAttributes->value) {
                # code...
                $notification=array(
                    'message'=>"Kindly Change Store to Main Store",
                    'alert-type'=>'danger',
                );
                return redirect()->route('invoices')->with($notification);
            }
            // Auth::InventoryStore()->store_id;
            InventoryInvoice::create([
                'supplier'=>$supplier,
                'created_by'=>Auth::user()->id,
                'store'=>$store->store_id,
                'uuid'=>$uuid
            ]);
            $inventoryInvoice = InventoryInvoice::where('uuid','=',$uuid)->get()->first();
            if ($invoiceNumber != "" ) {
                # code...
                InventoryInvoiceNumber::create([
                    'invoice'=>$inventoryInvoice->invoice_id,
                    'source'=>2,
                    'value'=>$invoiceNumber,
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
                InventoryInvoiceNumber::create([
                    'invoice'=>$inventoryInvoice->invoice_id,
                    'source'=>1,
                    'value'=>$this->createInvoiveNumber(),
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
            }else {
                # code...
                InventoryInvoiceNumber::create([
                    'invoice'=>$inventoryInvoice->invoice_id,
                    'source'=>1,
                    'value'=>$this->createInvoiveNumber(),
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
            foreach ($item as $k => $v) {
                # code...
                $itemBatches=InventoryItemBatch::where('item',$item[$k])->where('batch_no',$batch[$k])->get();
                if (!$itemBatches->isEmpty()) {
                    # code...
                    $itemBatch=$itemBatches->first();
                }else {
                    # code...
                    $itemBatch=InventoryItemBatch::create([
                        'item'=>$item[$k],
                        'batch_no'=>$batch[$k],
                        'expire_date'=>$expiryDate[$k],
                        'created_by'=>Auth::user()->id,
                        'uuid'=>(string)Str::uuid()
                    ]);
                    $itemBatch=InventoryItemBatch::where('batch_no',$batch[$k])->get()->first();
                }
                InventoryInvoiceItem::create([
                    'invoice'=>$inventoryInvoice->invoice_id,
                    'batch'=>$itemBatch->bath_reference_id,
                    'units'=>$unit[$k],
                    'quantity'=>$qty[$k],
                    'batch_quantity'=>$qty[$k],
                    'unit_price'=>$uprice[$k],
                    'created_by'=>Auth::user()->id,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
            $notification=array(
                'message'=>"Item Invoice has been added",
                'alert-type'=>'success',
            );
            DB::commit();
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('invoices')->with($notification);
        }
        return redirect()->route('invoices')->with($notification);    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryInvoice  $inventoryInvoice
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryInvoice $inventoryInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryInvoice  $inventoryInvoice
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryInvoice $inventoryInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        try {
            //code...
            DB::beginTransaction();
            $store = InventoryStore::where('uuid','=',session('storeUuid'))->get()->first();
            $inventoryInvoice= InventoryInvoice::where('invoice_id','=',$request->invoiceId)->where('store','=',$store->store_id);
            $inventoryInvoice->update([
                'completed' => 1,
                'completed_by' => Auth::user()->id,
                'changed_by'=>Auth::user()->id,
                'date_changed'=>Carbon::now()
            ]);
            $invoice_id=$inventoryInvoice->get()->first()->invoice_id;
            $invoiceItems=InventoryInvoiceItem::where('invoice',$invoice_id)->get();
            foreach ($invoiceItems as $invoiceItem) {
                # code...
                $itemBatch=InventoryItemBatch::where('bath_reference_id',$invoiceItem->batch)->where('voided',0)->get()->first();
                $stockOnHand=InventoryStockOnHand::where('store',$store->store_id)->where('item',$itemBatch->item);
                $stockOnBatch=InventoryStockOnHandByBatch::where('store',$store->store_id)->where('batch',$invoiceItem->batch);
                $transactions=InventoryTransaction::where('store',$store->store_id)->where('batch',$invoiceItem->batch);
                $uuid=(string)Str::uuid();
                if ($stockOnHand->get()->isEmpty()) {
                    # code...
                    InventoryStockOnHand::create([
                        'store'=>$store->store_id,
                        'item'=>$itemBatch->item,
                        'quantity'=>$invoiceItem->quantity,
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                }else {
                    # code...
                    $oldStockOnHand=$stockOnHand->get()->first();
                    $stockOnHand->update([
                        'quantity'=>$oldStockOnHand->quantity + $invoiceItem->quantity
                    ]);
    
                }
                if ($stockOnBatch->get()->isEmpty()) {
                    # code...
                    InventoryStockOnHandByBatch::create([
                        'store'=>$store->store_id,
                        'batch'=>$invoiceItem->batch,
                        'quantity'=>$invoiceItem->quantity,
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                } else {
                    # code...
                    $oldStockOnBatch=$stockOnBatch->get()->first();
                    $stockOnBatch->update([
                        'quantity'=>$oldStockOnBatch->quantity + $invoiceItem->quantity
                    ]);
                }
                if ($transactions->get()->isEmpty()) {
                    # code...
                    InventoryTransaction::create([
                        'batch'=>$invoiceItem->batch,
                        'store'=>$store->store_id,
                        'source'=>1,
                        'type'=>1,
                        'quantity'=>$invoiceItem->quantity,
                        'quantity_before'=>0,
                        'quantity_after'=>($invoiceItem->quantity),
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]);
                } else {
                    # code...
                    $transactions = $transactions->get()->last();
                    InventoryTransaction::create([
                        'batch'=>$invoiceItem->batch,
                        'store'=>$store->store_id,
                        'source'=>1,
                        'type'=>1,
                        'quantity'=>$invoiceItem->quantity,
                        'quantity_before'=>$transactions->quantity_after,
                        'quantity_after'=>($transactions->quantity_after + $invoiceItem->quantity),
                        'created_by'=>Auth::user()->id,
                        'uuid'=>$uuid
                    ]); 
                }
                $transaction_id=InventoryTransaction::where('uuid',$uuid)->get()->first()->transaction_id;
                InventoryTransactionInvoiceItem::create([
                    'transaction'=>(integer)$transaction_id,
                    'invoice_item'=>$invoiceItem->invoice_item_id,
                    'created_by'=>Auth::user()->id,
                    'uuid'=>$uuid
                ]);
            }
            DB::commit();
            $notification=array(
                'message'=>"Invoice has been Approved",
                'alert-type'=>'success',
            );
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            $notification=array(
                'message'=>$th->getMessage(),
                'alert-type'=>'danger',
            );
            return redirect()->route('invoices')->with($notification);
        }
        return redirect()->route('invoices')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryInvoice  $inventoryInvoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryInvoice $inventoryInvoice)
    {
        //
    }
}
