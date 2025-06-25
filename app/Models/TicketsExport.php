<?php namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BookingsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Booking::with('branch')->get(); // adjust relationships as needed
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Booking No.',
            'Branch',
            'First Name',
            'Last Name',
            'Phone',
            'Journey Type',
            'Ticket Type',
            'Date',
            'Time',
            'Status',
        ];
    }

    public function map($booking): array
    {
        static $i = 1;
        return [
            $i++,
            $booking->booking_number,
            $booking->branch->name ?? '',
            $booking->first_name,
            $booking->last_name,
            $booking->phone_no,
            $booking->booking_type,
            $booking->ticket_type == 1 ? 'CLASSIC' : 'VIP',
            \Carbon\Carbon::parse($booking->date)->format('d/m/Y'),
            $booking->time,
            $booking->status,
        ];
    }
}

 ?>