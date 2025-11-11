<?php

namespace App\Exports;

use App\Models\Deliverablecities;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DeliverablecitiesExport implements FromQuery, WithHeadings
{
    use Exportable;

    protected array $columns;

    public function __construct()
    {
        // Dynamically fetch ALL DB columns for the model's table
        $this->columns = Schema::getColumnListing((new Deliverablecities)->getTable());
    }

    public function query()
    {
        // Select all columns explicitly to keep order consistent
        return Deliverablecities::query()->where('is_active', 1)->select($this->columns);
    }

    public function headings(): array
    {
        // Use raw column names as Excel headings
        return $this->columns;
    }
}
