<?php


namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariations;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Support\Str;
use App\Models\User;

class  ProductService
{
    public $product, $store, $user, $storeProduct, $productVariations;
    public function __construct(Product $product, User $user, Store $store, StoreProduct $storeProduct, ProductVariations $productVariations)
    {
        $this->product = $product;
        $this->productVariations = $productVariations;
        $this->user = $user;
        $this->store = $store;
        $this->storeProduct = $storeProduct;
    }

    public function getListProduct($page = 1, $keyword = '')
    {
        $perpage = 10;
        $offset = ($page - 1) * $perpage;
        $query = $this->product->orderByDesc('created_at')->skip($offset)->take($perpage);
        $query->where('deleted', false);

        if ($keyword) {
            $query->where('name', 'like', "%$keyword%");
            $query->orWhere('code', 'like', "%$keyword%");
        }
        $data = $query->get()->toArray();
        return response()->json(['data' => $data]);
    }

    public function detailProduct($code)
    {
        $data = $this->product->where('code', $code)->with('productVariations')->first();
        return response()->json(['data' => $data]);
    }

    public function createProduct($data)
    {
        if ($data['store_id']) {
            $store = $this->store->where('id', $data['store_id'])->first();
            if (!$store) {
                return response()->json([
                    'message' => 'Not found store!!'
                ], 422);
            }
        }
        $dataSave = [
            'code' => Str::random(10),
            'name' => $data['name']
        ];

        $this->product->fill($dataSave);
        $this->product->save();
        $product = $this->product;
        if ($product) {
            if ($store) {
                $this->storeProduct->fill([
                    'store_id' => $store->id,
                    'product_id' => $product->id
                ]);
                $this->storeProduct->save();
            }
            $dataSaveProductVars = [];
            foreach ($data['skus'] as $key => $value) {
                array_push($dataSaveProductVars, [
                    'variation' => $value['variation'],
                    'price' => $value['price'],
                    'product_id' => $product->id,
                    'sku' => Str::random(6),

                ]);
            }
            $this->productVariations->insert($dataSaveProductVars);
        }
        return response()->json(['data' => $this->product]);
    }

    public function destroyProduct($code)
    {
        $product = $this->product->where('code', $code)->first();
        if (!$product) {
            return response()->json([
                'message' => 'Not found store!!'
            ], 422);
        }
        $this->product->where('id', $product->id)->update([
            'deleted' => true
        ]);
        $this->storeProduct->where('product_id', $product->id)->update([
            'deleted' => true
        ]);
        $this->productVariations->where('product_id', $product->id)->update([
            'deleted' => true
        ]);
        return response()->json([
            'message' => 'Delete Succesed!!'
        ]);
    }
}
