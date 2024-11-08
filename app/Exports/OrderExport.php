<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrderExport implements FromCollection, WithHeadings
{
    protected $history;
    protected $month;

    public function __construct(Collection $history, $month)
    {
        $this->history = $history;
        $this->month = $month;
    }

    public function collection()
    {
        return $this->history->filter(function ($item) {
            return $item->created_at->month == $this->month;
        })->map(function ($item) {
            return [
                'No' => $item->id,
                'Date' => $item->created_at->format('Y-m-d'),
                'Order Id' => $item->no_order,
                'Nama' => $item->name,
                'Order' => $item->order,
                'Total' => $item->total_amount,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Date',
            'Order Id',
            'Nama',
            'Order',
            'Total',
            'Status',
        ];
    }

    protected function formatOrder($order)
    {
        // Implement your logic to format the order data if needed
        return $order;
    }
}
