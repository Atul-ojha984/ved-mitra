@extends('pandit.layout')
@section('title', 'Calendar')
@section('page_title', 'Calendar')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
@endpush

@section('content')

<div class="flex flex-wrap gap-3 mb-6 text-xs font-bold">
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Confirmed</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-yellow-500 inline-block"></span> Pending</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Completed</span>
    <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-gray-400 inline-block"></span> Blocked</span>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <div id="calendar"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var cal = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,listWeek' },
        height: 'auto',
        events: '{{ route("pandit.calendar.events") }}',
        eventClick: function(info) {
            if (info.event.extendedProps.type === 'booking') {
                alert(info.event.title + '\nStatus: ' + info.event.extendedProps.status);
            }
        },
        eventTimeFormat: { hour: 'numeric', minute: '2-digit', meridiem: 'short' },
        nowIndicator: true,
        dayMaxEventRows: 3,
    });
    cal.render();
});
</script>
@endsection
