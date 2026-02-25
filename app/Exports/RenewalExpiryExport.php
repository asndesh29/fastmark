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
        $this->renewalTypes = RenewalType::where('is_active', true)->get();
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

        // Filter by date range
        if (!empty($this->filters['from_date']) && !empty($this->filters['to_date'])) {
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
        $baseHeaders = ['Vehicle No', 'Vehicle Code', 'Owner Name', 'Mobile'];

        if (!empty($this->filters['renewal_type_id'])) {
            $type = $this->renewalTypes->firstWhere('id', $this->filters['renewal_type_id']);
            $baseHeaders[] = $type->name;
            $baseHeaders[] = 'Expiry Days';
        } else {
            // If no filter, include all active types
            foreach ($this->renewalTypes as $type) {
                $baseHeaders[] = $type->name;
                $baseHeaders[] = 'Expiry Days';
            }
        }

        return $baseHeaders;
    }

    public function map($vehicle): array
    {
        $today = Carbon::today();
        $row = [
            $vehicle->registration_no,
            substr($vehicle->registration_no, -4),
            $vehicle->owner?->first_name . ' ' . $vehicle->owner?->last_name ?? '-',
            $vehicle->owner?->phone ?? '-',
        ];

        if (!empty($this->filters['renewal_type_id'])) {
            $type = $this->renewalTypes->firstWhere('id', $this->filters['renewal_type_id']);
            $renewal = $vehicle->renewals->where('renewal_type_id', $type->id)->first();

            if ($renewal?->start_date_ad) {
                $expiryDate = Carbon::parse($renewal->start_date_ad)->startOfDay();
                $daysLeft = $today->diffInDays($expiryDate, false);
                $row[] = $renewal->start_date_bs;
                $row[] = $daysLeft;
            } else {
                $row[] = '-';
                $row[] = '-';
            }
        } else {
            foreach ($this->renewalTypes as $type) {
                $renewal = $vehicle->renewals->where('renewal_type_id', $type->id)->first();

                if ($renewal?->start_date_ad) {
                    $expiryDate = Carbon::parse($renewal->start_date_ad)->startOfDay();
                    $daysLeft = $today->diffInDays($expiryDate, false);
                    $row[] = $renewal->start_date_bs;
                    $row[] = $daysLeft;
                } else {
                    $row[] = '-';
                    $row[] = '-';
                }
            }
        }

        return $row;
    }
}