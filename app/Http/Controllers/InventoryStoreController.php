<?php

namespace App\Http\Controllers;

use App\Models\InventoryStore;
use App\Models\InventoryStoreAttribute;
use App\Models\InventoryStoreAttributeType;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class InventoryStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $viewStores="View Strore";
        $title="View Stores";
        $users = User::get();
        $stores= InventoryStore::where('retired',0)->get();
        return view('inventory.stores',compact(
            'stores','viewStores','title','users'
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
        $title = "Create Store";
        $createStores="Create Store";
        $attributesType = InventoryStoreAttributeType::where('retired',0)->get();
        return view('inventory.stores',compact(
            'title','createStores','attributesType',
        ));
    }

    public function attributeTypeId($name){
        $attributes= InventoryStoreAttributeType::get()->where('name',$name);
        return $attributes->type_id;
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
        $name=$request->name;
        $description= $request->description;
        $attributrType=$request->attributrType;
        $user = Auth::user()->id;
        $uuid=(string)str::uuid();
        
         InventoryStore::create([
            'name'=>$name,
            'description'=>$description,
            'created_by'=>$user,
            'uuid'=>$uuid
        ]);
        $store  = InventoryStore::where('uuid','=',$uuid)->get()->first();
        if ($store) {
            # code...
            foreach ($attributrType as $k => $v) {
                # code...
                $uuid=(string)str::uuid();
                InventoryStoreAttribute::create([
                    'store'=>$store->store_id,
                    'type'=>$attributrType[$k],
                    'value'=>'Yes',
                    'created_by'=>$user,
                    'uuid'=>$uuid,
                ]);
            }
        }
        $notification=array(
            'message'=>"Store has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('stores')->with($notification);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InventoryStore  $inventoryStore
     * @return \Illuminate\Http\Response
     */
    public function show(InventoryStore $inventoryStore)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InventoryStore  $inventoryStore
     * @return \Illuminate\Http\Response
     */
    public function edit(InventoryStore $inventoryStore)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InventoryStore  $inventoryStore
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InventoryStore $inventoryStore)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InventoryStore  $inventoryStore
     * @return \Illuminate\Http\Response
     */
    public function destroy(InventoryStore $inventoryStore)
    {
        //
    }
}
