<?php

namespace App\Http\Services;

use App\Models\FeeSlab;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;

class FeeSlabService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        return FeeSlab::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function store($data)
    {
        // dd($data);
        return FeeSlab::create($data);
    }

    public function getById($id)
    {
        return FeeSlab::findOrFail($id);
    }

    public function update(FeeSlab $feeSlab, $data)
    {
        return $feeSlab->update($data);
    }

    public function destroy($id)
    {
        $customer = $this->getById($id);

        if (!$customer) {
            return false;
        }

        return $customer->delete();
    }
}