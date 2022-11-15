<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $products = Product::when($request, function($query, $request) {
            $model = new Product();
            $fillable = $model->getFillable();
            $fieldsByFilter = array_flip($fillable);

            //Remove field 'description' and 'price' of filter
            unset($fieldsByFilter['description'], $fieldsByFilter['price']);

            foreach ($request->toArray() as $key => $value) {
                if (array_key_exists($key, $fieldsByFilter) && $request->has($key)) {

                    if ($key === 'name') {
                        $query->where($key, 'LIKE', "%{$request[$key]}%");
                    }

                    if (in_array($key, ['category', 'id'])) {
                        $query->where($key, '=', $request[$key]);
                    }

                    if ($key === 'image_url' && in_array($request[$key], ['true', 'false'])) {
                        $compare = $request[$key] === 'true' ? '<>' : '=' ;
                        $query->where($key, $compare, null);
                    }

                }
            }
       });

        return ProductResource::collection($products->get());
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $products = Product::create($request->all());

        return new ProductResource($products);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(null, 204);
    }
}
