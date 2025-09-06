<?php

namespace App\Http\Services;

use App\Models\Customer;
use App\Helpers\AppHelper;
use Illuminate\Http\Request;

class CustomerService 
{
    public function list(Request $request, $perPage = null) 
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        return Customer::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function store($data) 
    {
        if (isset($data['image'])) {
            $data['image'] = AppHelper::upload('customer', 'png', $data['image']);
        }
        return Customer::store($data);
    }

    public function getById($id) 
    {
        return Customer::findOrFail($id);
    }

    public function update(Customer $customer, $data) 
    {
        if (isset($data['image'])) {
            $data['image'] = AppHelper::update('customer', $customer->image, 'png', $data['image']);
        }

        return $customer->update($data);
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