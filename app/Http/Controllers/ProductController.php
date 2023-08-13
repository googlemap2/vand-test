<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    protected $productService;
    public function __construct(ProductService $productService)
    {
        parent::__construct();
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $data = $request->query();
        $page = empty($data['page']) ? 1 : $data['page'];
        $keyword = empty($data['keyword']) ? '' : $data['keyword'];
        return $this->productService->getListProduct($page, $keyword);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'store_id' => 'required',
            'skus' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }
        $body = $request->getContent();
        $body = json_decode($body, true);
        if ($body['skus']) {
            foreach ($body['skus'] as $key => $sku) {
                if (!$sku['variation'] || !$sku['price']) {
                    return response()->json([
                        'message' => "Missing field variation or price in sku"
                    ], 422);
                }
            }
        }

        return $this->productService->createProduct($body);
    }
    public function show($code)
    {
        return $this->productService->detailProduct($code);
    }

    public function destroy($code)
    {
        return $this->productService->destroyProduct($code);
    }
    public function update(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'skus' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }
        $body = $request->getContent();
        $body = json_decode($body, true);
        return $this->productService->updateProduct($code, $body);
    }
}
