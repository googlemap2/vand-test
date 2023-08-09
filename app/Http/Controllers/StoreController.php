<?php

namespace App\Http\Controllers;

use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    protected $storeService;
    public function __construct(StoreService $storeService)
    {
        $this->storeService = $storeService;
    }
    public function index(Request $request)
    {
        $data = $request->query();
        $page = empty($data['page']) ? 1 : $data['page'];
        $keyword = empty($data['keyword']) ? '' : $data['keyword'];
        return $this->storeService->getListStore($page, $keyword);
    }
}
