<?php

namespace App\Services;

use App\Models\RenewalType;
use App\Models\Renewal;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Generic\GenericDateConverter\GenericDateConvertHelper;

class BaseRenewalService
{
    /**
     * Handle renewal for any renewable model
     *
     * @param  Model  $model   (Bluebook, Insurance, etc.)
     * @param  array  $data
     * @return Model
     */
    public function renew($model, array $data)
    {
        DB::beginTransaction();

        try {

            // Get renewal type using slug
            $renewalType = RenewalType::where('slug', $data['type'])->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Convert Nepali Date → English → Add 1 Year → Convert Back
            |--------------------------------------------------------------------------
            */

            $engStartDate = GenericDateConvertHelper::convertNepaliDateToEnglishYMDWithSep(
                $data['expiry_date_bs'],
                '-'
            );

            $engExpiryDate = Carbon::parse($engStartDate)
                ->addYear()
                ->format('Y-m-d');

            $newExpiryBs = GenericDateConvertHelper::convertEnglishDateToNepaliYMDWithSep(
                $engExpiryDate,
                '-'
            );

            /*
            |--------------------------------------------------------------------------
            | Update Main Model (Bluebook, Insurance, etc.)
            |--------------------------------------------------------------------------
            */

            $model->update([
                'renewed_expiry_date_bs' => $newExpiryBs,
                'renewed_expiry_date_ad' => $engExpiryDate,
                'payment_status' => $data['payment_status'],
                'remarks' => $data['remarks'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Renewal History
            |--------------------------------------------------------------------------
            */

            $model->renewals()->create([
                'renewal_type_id' => $renewalType->id,
                'status' => $data['payment_status'],
                'start_date_bs' => $data['expiry_date_bs'],
                'expiry_date_bs' => $newExpiryBs,
                'start_date_ad' => $engStartDate,
                'expiry_date_ad' => $engExpiryDate,
                'reminder_date' => Carbon::parse($engExpiryDate)->subDays(7),
                'remarks' => $data['remarks'] ?? null,
            ]);

            DB::commit();

            return $model->fresh();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
