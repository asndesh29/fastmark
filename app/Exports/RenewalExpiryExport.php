<?php

namespace App\Exports;

use App\Models\Renewal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RenewalExpiryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Renewal::with(['vehicle', 'renewalType']);

        if (!empty($this->filters['renewal_type_id'])) {
            $query->where('renewal_type_id', $this->filters['renewal_type_id']);
        }

        if (!empty($this->filters['from_date']) && !empty($this->filters['to_date'])) {
            $query->whereBetween('expiry_date', [
                $this->filters['from_date'],
                $this->filters['to_date']
            ]);
        }

        return $query->orderBy('expiry_date', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'Vehicle',
            'Renewal Type',
            'Start Date',
            'Expiry Date',
            'Status',
        ];
    }

    public function map($renewal): array
    {
        return [
            $renewal->vehicle?->registration_no,
            $renewal->renewalType?->name,
            $renewal->start_date,
            $renewal->expiry_date,
            $renewal->status,
        ];
    }
}

