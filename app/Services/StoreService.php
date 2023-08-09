<?php

namespace App\Services;

use App\Models\Store;
use Illuminate\Support\Facades\DB;

class  StoreService
{
    public $store;
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    public function getListStore($page = 1, $keyword = '')
    {
        $perpage = 10;
        $offset = ($page - 1) * $perpage;
        $query = Store::orderByDesc('created_at')->skip($offset)->take($perpage);
        if ($keyword) {
            $query->where('name', 'like', "%$keyword%");
            $query->orWhere('code', 'like', "%$keyword%");
        }
        $data = $query->get()->toArray();
        dd($data);
    }
}
