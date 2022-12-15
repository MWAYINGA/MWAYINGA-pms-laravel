<?php

namespace App\Http\Controllers;

use App\Models\ItemCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreItemCategoryRequest;
use App\Http\Requests\UpdateItemCategoryRequest;
use Illuminate\Support\Str;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class ItemCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $title = "Item Units";
        $users = User::get();
        $categories = ItemCategory::where('voided','=',0)->get();
        //
        return view('item-category',compact(
            'title','categories','users'
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
     * @param  \App\Http\Requests\StoreItemCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreItemCategoryRequest $request)
    {
        //
        $this->validate($request,[
            'name'=>'required|max:100',
        ]);
        $uuid=(string)Str::uuid();
        $userId=Auth::user()->id;
        ItemCategory::create([
            'name'=>$request->name,
            'uuid'=>$uuid,
            'created_by'=>$userId
        ]);
        $notification=array(
            'message'=>"Item Units has been added",
            'alert-type'=>'success',
        );
        return redirect()->route('itemCategory')->with($notification);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ItemCategory $itemCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(ItemCategory $itemCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateItemCategoryRequest  $request
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateItemCategoryRequest $request, ItemCategory $itemCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ItemCategory  $itemCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        //
        $this->validate($request,['reason'=>'required|max:100']);
        $itemCategory = ItemCategory::where('category_id','=',$request->id);
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
