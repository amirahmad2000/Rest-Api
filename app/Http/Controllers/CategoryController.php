<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\CategoryResource;
use App\Http\Controllers\ApiController;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(2);
        return $this->successResponse([

            'categories'=>CategoryResource::collection($categories),
            'links'=>CategoryResource::collection($categories)->response()->getData()->links,
            'meta'=>CategoryResource::collection($categories)->response()->getData()->meta,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        DB::beginTransaction();

        $category = Category::create([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
        ]);

        DB::commit();

        return $this->successResponse(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->successResponse(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'parent_id' => 'required|integer',
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        DB::beginTransaction();

        $category->update([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
        ]);

        DB::commit();

        return $this->successResponse(new CategoryResource($category), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return $this->successResponse(new CategoryResource($category), 201);
    }

    public function children(Category $category){
        return $this->successResponse(new CategoryResource($category->load('children')));
    }

    public function parent(Category $category){
        return $this->successResponse(new CategoryResource($category->load('parent')));
    }

    public function products(Category $category){
        return $this->successResponse(new CategoryResource($category->load('products')));
    }
}
