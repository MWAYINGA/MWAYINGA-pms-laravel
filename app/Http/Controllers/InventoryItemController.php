<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ItemCategory;
use App\Models\ItemGroup;
use Illuminate\Http\Request;

use App\Models\User;
use Carbon\Carbon;
use App\Models\ItemUnits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class InventoryItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = "Item List";
        $users = User::get();
        $units = ItemUnits::where('voided','=',0)->get();
        $categories = ItemCategory::where('voided','=',0)->get();
        $groups = ItemGroup::where('voided','=',0)->get();
        $items = InventoryItem::where('voided','=',0)->get();
        return view('inventory.items-list',compact(
            'title','users','units','categories','groups','items'
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
        $title= "Add Item";
        $units = ItemUnits::where('voided','=',0)->get();
        $categories = ItemCategory::where('voided','=',0)->get();
        $groups = ItemGroup::where('voided','=',0)->get();
        return view('inventory.add-item',compact(
            'title','units','categories','groups',
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
            'name'=>'required|max:200',
            'group'=>'required',
            'units'=>'required',
            'category'=>'required',
        ]);
        $uuid=(string)Str::uuid();
        $sku=null;
        $userId=Auth::user()->id;
        $inventoryItem = InventoryItem ::orderBy('inv_item_id','desc')->get()->first();
        if (!$inventoryItem) {
            $sku= 10001;
        }else {
            $sku= $inventoryItem->sku + 1;
        }
        InventoryItem::create([
            'name' => $request->name,
            'group' => $request->group,
            'sku' => $sku,
            'strength' => $request->strength,
            'description' => $request->description,
            'prescription' => $request->prescription,
            'category' => $request->category,
            'units' => $request->units,
            'uuid' => $uuid,
            'created_by' => $userId,
        ]);
        $notification=array(
            'message'=>"Item has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('items')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryItem  $inventoryItem
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $item)
    {
        //
       
        $title= "Add Item";
        $units = ItemUnits::where('voided','=',0)->get();
        $categories = ItemCategory::where('voided','=',0)->get();
        $groups = ItemGroup::where('voided','=',0)->get();
        // $item = InventoryItem::where('inv_item_id', '=',$item);
        $item = InventoryItem::where('inv_item_id','=',$item)->get()->first();
        return view('inventory.edit-item',compact(
            'title','item','units','categories','groups',
        ));
    }

    public function outstock(){
        //
        $title = "Item List";
        $users = User::get();
        $units = ItemUnits::where('voided','=',0)->get();
        $categories = ItemCategory::where('voided','=',0)->get();
        $groups = ItemGroup::where('voided','=',0)->get();
        $items = InventoryItem::where('voided','=',0)->get();
        return view('inventory.out-stock-items',compact(
            'title','users','units',
        ));

    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryItem  $inventoryItem
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryItem $inventoryItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryItem  $inventoryItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$item)
    {
        //
        $this->validate($request,[
            'name'=>'required|max:200',
            'group'=>'required',
            'units'=>'required',
            'category'=>'required',
        ]);
        $inventoryItem= InventoryItem::where('inv_item_id','=',$item);
        // $prescription = 0;
        $prescription = ($request->prescription > 0) ? 1 : 0 ;
       $inventoryItem->update([
        'name' => $request->name,
        'group' => $request->group,
        'strength' => $request->strength,
        'description' => $request->description,
        'prescription' => $prescription,
        'category' => $request->category,
        'units' => $request->units,
        'changed_by'=>Auth::user()->id,
        'date_changed'=>Carbon::now()

        ]);
        $notification=array(
            'message'=>"Item has been updated",
            'alert-type'=>'success',
        );
        return redirect()->route('items')->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryItem  $inventoryItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $this->validate($request,['reason'=>'required|max:100']);
        $inventoryItem = InventoryItem::where('inv_item_id','=',$request->id);        
        $inventoryItem->update([
            'voided'=>1,
            'voided_date'=>Carbon::now(),
            'voided_reason'=>$request->reason,
            'voided_by'=>Auth::user()->id
        ]);

        $notification = array(
            'message'=>"Item Unit has been deleted",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }
}
