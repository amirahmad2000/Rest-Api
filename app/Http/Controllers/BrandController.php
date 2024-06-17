<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BrandResource;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Validator;

class BrandController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::paginate(2);
        return $this->successResponse([

            'brands'=>BrandResource::collection($brands),
            'links'=>BrandResource::collection($brands)->response()->getData()->links,
            'meta'=>BrandResource::collection($brands)->response()->getData()->meta,
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
            'name' => 'required',
            'display_name' => 'required|unique:brands',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        DB::beginTransaction();

        $brand = Brand::create([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        DB::commit();

        return $this->successResponse(new BrandResource($brand), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->successResponse(new BrandResource($brand));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'display_name' => 'required|unique:brands',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->messages(), 422);
        }

        DB::beginTransaction();

        $brand->update([
            'name' => $request->name,
            'display_name' => $request->display_name,
        ]);

        DB::commit();

        return $this->successResponse(new BrandResource($brand), 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return $this->successResponse(new BrandResource($brand), 201);

    }

    public function products(Brand $brand){
        return $this->successResponse(new BrandResource($brand->load('products')));
    }
}
