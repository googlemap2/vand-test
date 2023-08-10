<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{
    protected $storeService;
    public function __construct(StoreService $storeService)
    {
        parent::__construct();
        $this->storeService = $storeService;
    }
    public function index(Request $request)
    {
        $data = $request->query();
        $page = empty($data['page']) ? 1 : $data['page'];
        $keyword = empty($data['keyword']) ? '' : $data['keyword'];
        return $this->storeService->getListStore($page, $keyword);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_name' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }
        $body = $request->getContent();
        $body = json_decode($body, true);
        return $this->storeService->createStore($body);
    }
    public function show($code)
    {
        return $this->storeService->detailStore($code);
    }

    public function destroy($code)
    {
        return $this->storeService->destroyStore($code);
    }
    public function update(Request $request, $code)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => implode(', ', $validator->errors()->all())
            ], 422);
        }
        $body = $request->getContent();
        $body = json_decode($body, true);
        return $this->storeService->updateStore($code, $body);
    }
}
