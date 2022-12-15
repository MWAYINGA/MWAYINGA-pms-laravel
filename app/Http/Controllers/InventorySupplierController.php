<?php

namespace App\Http\Controllers;

use App\Models\InventorySupplier;
use App\Models\InventorySupplierAttribute;
use App\Models\InventorySupplierAttributeType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class InventorySupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $viewSupplier="View Supplier";
        $title="View Supplier";
        $users = User::get();
        $attribute = InventorySupplierAttribute::where('voided','=',0)->get();
        $attributeType = InventorySupplierAttributeType::where('retired','=',0)->get();
        $suppliers= InventorySupplier::where('retired','=',0)->get();
        return view('inventory.supplier',compact(
            'title','users','viewSupplier','suppliers','attribute','attributeType'
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
        $title = "Create Supplier";
        $createSupplier="Create Supplier";
        $users = User::get();
        $attribute = InventorySupplierAttribute::where('voided','=',0)->get();
        $attributeType = InventorySupplierAttributeType::where('retired','=',0)->get();
        // $suppliers= InventorySupplier::where('retired','=',0)->get();
        return view('inventory.supplier',compact(
            'title','users','createSupplier','attribute','attributeType'
        ));
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
        $this->validate($request,[
            'name'=>'required|max:100',
        ]);
        $uuid=(string)Str::uuid();
        $userId=Auth::user()->id;
        $supplier=InventorySupplier::create([
            'name'=>$request->name,
            'description'=>$request->description,
            'uuid'=>$uuid,
            'created_by'=>$userId
        ]);
        $supplier  = InventorySupplier::where('uuid','=',$uuid)->get()->first();
        $attributeType=$request->attributeType;
        foreach ($attributeType as $k => $v) {
            # code...
            if ($v !="") {
                # code...
                InventorySupplierAttribute::create([
                    'supplier'=>$supplier->supplier_id,
                    'type'=>$k,
                    'value'=>$v,
                    'created_by'=>$userId,
                    'uuid'=>(string)Str::uuid()
                ]);
            }
        }
        $notification=array(
            'message'=>"Supplier has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('supplier')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventorySupplier  $inventorySupplier
     * @return \Illuminate\Http\Response
     */
    public function show(InventorySupplier $inventorySupplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventorySupplier  $inventorySupplier
     * @return \Illuminate\Http\Response
     */
    public function edit(InventorySupplier $inventorySupplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventorySupplier  $inventorySupplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventorySupplier $inventorySupplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventorySupplier  $inventorySupplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $this->validate($request,['reason'=>'required|max:100']);
        $itemCategory = InventorySupplier::where('category_id','=',$request->id);
        // ('unit_id', '=' ,$request->id)->get();
        // $itemUnits->delete();

        $itemCategory->update([
            'voided'=>1,
            'voided_date'=>Carbon::now(),
            'voided_reason'=>$request->reason,
            'voided_by'=>Auth::user()->id
        ]);

        $notification = array(
            'message'=>"Item Category has been deleted",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
}
