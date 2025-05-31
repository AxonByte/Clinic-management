@extends('layouts.app')

@section('content')
 <style>
    .fc .fc-daygrid-event {
  white-space: normal !important;
  height: auto !important;
  width: auto !important;
  overflow: visible !important;
  padding: 0 !important;
  border: none;
}

.fc-event-main {
  white-space: normal !important;
}

.fc-daygrid-day-frame {
  display: block;
}

.fc-daygrid-event-harness {
  height: auto !important;
}

.fc .fc-daygrid-day {
  min-height: 120px;
  vertical-align: top;
}

/* .fc-daygrid-day {
  min-width: 200px !important;
}

.fc .fc-col-header-cell {
  min-width: 200px !important;
}

#calendar {
  min-width: 1400px; 
} */

 </style>
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
                  <div style="overflow-x: auto;">
                    <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- FullCalendar CSS & JS -->
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>


<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      fixedWeekCount: false,
      contentHeight: 'auto',
      dayMaxEventRows: true,
      events: [
       @foreach($appointments as $appointment)
    @php
        $color = match(strtolower($appointment->status)) {
            'confirmed' => '#198754',
            'requested', 'pending confirmation' => '#ffc107',
            'treated' => 'green',
            'cancelled' => 'red',
            default => '#0dcaf0',
        };

        // Parse time range
        $times = explode('-', $appointment->time_slot);
        $startTime = isset($times[0]) ? \Carbon\Carbon::parse(trim($times[0]))->format('h:i A') : '';
        $endTime = isset($times[1]) ? \Carbon\Carbon::parse(trim($times[1]))->format('h:i A') : '';
        @endphp

        {
        title: `<div class="fc-appointment-card text-white rounded p-2 mt-2" style="background-color: {{ $color }}">
                    {{ $startTime }} - {{ $endTime }}<br>
                    Status: {{ $appointment->status }}<br>
                    Patient: {{ $appointment->patient->name }}<br>
                    Phone: {{ $appointment->patient->phone }}<br>
                    Doctor: {{ $appointment->doctor->name }}<br>
                    Remarks: {{ $appointment->description }}
                </div>`,
        start: '{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') }}',
        allDay: true,
        },
    @endforeach

      ],
      eventContent: function (arg) {
        return { html: arg.event.title };
      }
    });

    calendar.render();
  });
</script>

@endsection
