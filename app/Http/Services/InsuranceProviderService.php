<?php

namespace App\Http\Services;

use App\Models\InsuranceProvider;
use Illuminate\Http\Request;

class InsuranceProviderService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        $providers = InsuranceProvider::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);

        return $providers;
    }

    public function store($data)
    {
        // dd($data);
        return InsuranceProvider::create($data);
    }

    public function getById($id)
    {
        return InsuranceProvider::findOrFail($id);
    }

    public function update(InsuranceProvider $insuranceProvider, $data)
    {
        return $insuranceProvider->update($data);
    }

    public function destroy($id)
    {
        $insuranceProvider = $this->getById($id);

        if (!$insuranceProvider) {
            return false;
        }

        return $insuranceProvider->delete();
    }
}