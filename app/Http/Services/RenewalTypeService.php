<?php

namespace App\Http\Services;

use App\Generic\GenericDateConverter\GenericDateConvertHelper;
use App\Models\Bluebook;
use App\Models\Customer;
use App\Models\Renewal;
use App\Models\RenewalType;
use App\Models\Vehicle;
use App\Models\VehicleType;
use App\Models\RoadPermit;
use App\Models\PollutionCheck;
use App\Models\VehiclePass;
use App\Models\Insurance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RenewalTypeService
{
    public function list(Request $request, $perPage = null)
    {
        $keywords = explode(' ', $request->search ?? '');
        $perPage = $perPage ?? config('default_pagination', 10);

        return RenewalType::when($request->search, function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->orWhere('name', 'like', "%{$word}%");
            }
        })->orderBy('created_at', 'desc')->paginate($perPage);

    }

    public function store(array $data)
    {
        return RenewalType::create($data);
    }

    public function getById($id)
    {
        return RenewalType::findOrFail($id);
    }

    public function update(RenewalType $renewalType, $data)
    {
        return $renewalType->update($data);
    }

    public function delete($id)
    {
        $renewalType = $this->getById($id);

        if (!$renewalType) {
            return false;
        }

        return $renewalType->delete();
    }
}