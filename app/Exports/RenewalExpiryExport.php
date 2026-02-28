<?php

namespace App\Exports;

use App\Models\Vehicle;
use App\Models\RenewalType;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class RenewalExpiryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;
    protected $renewalTypes;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        $this->renewalTypes = RenewalType::where('is_active', true)
            ->where('slug', '!=', 'license') // match blade
            ->get();
    }

    public function collection()
    {
        $query = Vehicle::with(['renewals.renewalType', 'owner']);

        // Filter by renewal type
        if (!empty($this->filters['renewal_type_id'])) {
            $query->whereHas('renewals', function ($sub) {
                $sub->where('renewal_type_id', $this->filters['renewal_type_id']);
            });
        }

        // Apply date filter ONLY if renewal type selected
        if (
            !empty($this->filters['renewal_type_id']) &&
            !empty($this->filters['from_date']) &&
            !empty($this->filters['to_date'])
        ) {
            $query->whereHas('renewals', function ($sub) {
                $sub->whereBetween('expiry_date_bs', [
                    $this->filters['from_date'],
                    $this->filters['to_date']
                ]);
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        $headers = ['Vehicle No', 'Vehicle Code', 'Owner Name', 'Mobile'];

        if (!empty($this->filters['renewal_type_id'])) {

            $type = $this->renewalTypes
                ->firstWhere('id', $this->filters['renewal_type_id']);

            if ($type) {
                $headers[] = $type->name;           // AD Date
                $headers[] = $type->name . ' Miti'; // BS Date
                $headers[] = 'Expiry Days';
            }

        } else {

            foreach ($this->renewalTypes as $type) {
                $headers[] = $type->name;           // AD
                $headers[] = $type->name . ' Miti'; // BS
                $headers[] = 'Expiry Days';
            }
        }

        return $headers;
    }

    public function map($vehicle): array
    {
        $today = Carbon::today();

        $row = [
            $vehicle->registration_no,
            substr($vehicle->registration_no, -4),
            trim(($vehicle->owner?->first_name ?? '') . ' ' . ($vehicle->owner?->last_name ?? '')) ?: '-',
            $vehicle->owner?->phone ?? '-',
        ];

        if (!empty($this->filters['renewal_type_id'])) {

            $type = $this->renewalTypes
                ->firstWhere('id', $this->filters['renewal_type_id']);

            $renewal = $vehicle->renewals
                ->where('renewal_type_id', $type?->id)
                ->first();

            if ($renewal?->start_date_ad) {

                $expiryDate = Carbon::parse($renewal->start_date_ad)->startOfDay();
                $daysLeft = $today->diffInDays($expiryDate, false);

                $row[] = $renewal->start_date_ad; // AD
                $row[] = $renewal->start_date_bs; // BS
                $row[] = $daysLeft;

            } else {

                $row[] = '-';
                $row[] = '-';
                $row[] = '-';
            }

        } else {

            foreach ($this->renewalTypes as $type) {

                $renewal = $vehicle->renewals
                    ->where('renewal_type_id', $type->id)
                    ->first();

                if ($renewal?->start_date_ad) {

                    $expiryDate = Carbon::parse($renewal->start_date_ad)->startOfDay();
                    $daysLeft = $today->diffInDays($expiryDate, false);

                    $row[] = $renewal->start_date_ad; // AD
                    $row[] = $renewal->start_date_bs; // BS
                    $row[] = $daysLeft;

                } else {

                    $row[] = '-';
                    $row[] = '-';
                    $row[] = '-';
                }
            }
        }

        return $row;
    }
}