<?php


namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Store;
use App\Models\User;

class  StoreService
{
    public $store;
    public $user;
    public function __construct(Store $store, User $user)
    {
        $this->store = $store;
        $this->user = $user;
    }

    public function getListStore($page = 1, $keyword = '')
    {
        $perpage = 10;
        $offset = ($page - 1) * $perpage;
        $query = $this->store->orderByDesc('created_at')->skip($offset)->take($perpage);
        $query->where('deleted', false);

        if ($keyword) {
            $query->where('name', 'like', "%$keyword%");
            $query->orWhere('code', 'like', "%$keyword%");
        }
        $data = $query->with('user')->get()->toArray();
        return response()->json(['data' => $data]);
    }
    public function detailStore($code)
    {
        $data = $this->store->where('code', $code)->with('storeProduct')->first();
        return response()->json(['data' => $data]);
    }
    public function createStore($data)
    {
        $user = $this->user->where('user_name', $data['user_name'])->first();
        if (!$user) {
            return response()->json([
                'message' => 'Not found user!!'
            ], 422);
        }
        $dataSave = [
            "code" => Str::random(10),
            'user_id' => $user->id,
            "name" => $data['name']
        ];
        $this->store->fill($dataSave);
        $this->store->save();
        return response()->json(['data' => $this->store]);
    }

    public function destroyStore($code)
    {
        $store = $this->store->where('code', $code)->first();
        if (!$store) {
            return response()->json([
                'message' => 'Not found store!!'
            ], 422);
        }
        $this->store->where('id', $store->id)->update([
            'deleted' => true
        ]);;
        return response()->json([
            'message' => 'Delete Succesed!!'
        ]);
    }

    public function updateStore($code, $data)
    {
        $store = $this->store->where('code', $code)->where('deleted', false)->first();
        if (!$store) {
            return response()->json([
                'message' => 'Not found store!!'
            ], 422);
        }
        if (isset($data['code'])) {
            unset($data['code']);
        }
        if (isset($data['id'])) {
            unset($data['id']);
        }
        $this->store->where('id', $store->id)->update($data);
        return response()->json([
            'message' => 'Update Succesed!!'
        ]);
    }
}
