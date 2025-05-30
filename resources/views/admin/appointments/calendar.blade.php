@extends('layouts.app')

@section('content')
<div class="conatiner-fluid content-inner mt-n5 py-0">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">Appointment Calendar</h4>
                    </div>
                    <a class="btn btn-primary" href="{{ route('admin.appointment.index')}}">Appointment List</a>
                </div>
                <hr style="color: blue;">
                <div class="card-body">
                    <!-- Calendar -->
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            @foreach($appointments as $appointment)
            {
                title: `Status: {{ $appointment->status }}\nPatient: {{ $appointment->patient->name }}\nPhone: {{ $appointment->patient->phone }}\nDoctor: {{ $appointment->doctor->name }}\nRemarks: {{ $appointment->remarks }}`,
                start: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}',
                color: '{{ $appointment->status == "Confirmed" ? "#6f42c1" : ($appointment->status == "Requested" ? "#0dcaf0" : "#6c757d") }}'
            },
            @endforeach
        ]
    });

    calendar.render();
});
</script>
@endsection
