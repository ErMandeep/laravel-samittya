@extends('backend.layouts.app')

@push('after-styles')
    <style>


  #top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    font-size: 12px;
  }
  .left { float: left }
  .right { float: right }
  .clear { clear: both }

  #script-warning, #loading { display: none }
  #script-warning { font-weight: bold; color: red }

  #calendar {
    /*max-width: 900px;*/
    margin: 40px auto;
    padding: 0 10px;
    background:#fff;
  }

  .tzo {
    color: #000;
  }
    </style>
@endpush


@section('content')

<!--   <div id='top'>

    <div class='left'>
      Timezone:
      <select id='time-zone-selector'>
        <option value='local' selected>local</option>
        <option value='UTC'>UTC</option>
      </select>
    </div>

    <div class='right'>
      <span id='loading'>loading...</span>
      <span id='script-warning'><code>php/get-events.php</code> must be running.</span>
    </div>

    <div class='clear'></div>

  </div> -->


<div id="calendar">
	
</div>



@endsection

@push('after-scripts')

<script>
  $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

  document.addEventListener('DOMContentLoaded', function() {
    var initialTimeZone = 'local';
    var timeZoneSelectorEl = document.getElementById('time-zone-selector');
    var loadingEl = document.getElementById('loading');
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction','timeGrid' ],
      timeZone: initialTimeZone,
      defaultView: 'timeGridWeek',
      header: {
        left: '',
        center: '',
        right: '',
      },
      columnHeaderFormat: { weekday: 'short' },
      selectOverlap: false,
      selectMinDistance: 1,
      firstDay: 1,
      allDaySlot: false,
      defaultDate: '2019-06-12',
      navLinks: false, // can click day/week names to navigate views
      editable: true,
      selectable: true,
      eventLimit: true, // allow "more" link when too many events
      events: {
        url: 'php/get-events.php',
        failure: function() {
          // document.getElementById('script-warning').style.display = 'inline'; // show
        }
      },
      // loading: function(bool) {
      //   if (bool) {
      //     loadingEl.style.display = 'inline'; // show
      //   } else {
      //     loadingEl.style.display = 'none'; // hide
      //   }
      // },

      // eventTimeFormat: { hour: 'numeric', minute: '1-digit', timeZoneName: 'short' },

      dateClick: function(arg) {
        console.log('dateClick', calendar.formatIso(arg.date));
      },
      select: function(arg) {
        console.log(arg);
        console.log('select', arg.start, calendar.formatIso(arg.end));
        calendar.addEvent({
                title: '',
                id : 'shift_id',
                start: arg.start,
                end: arg.end,
                allDay: false,
                overlap: false,
                backgroundColor: 'rgba(251, 95, 44, 0.78)',
                textColor: '#fff',
                borderColor: 'rgba(251, 95, 44, 0.92)'
              });
        $.ajax({
                url: '{{ route("schedule.add") }}',
                type: 'POST',
                data: {
                  event_id: 'here',
                  event_start : arg.start,
                  event_end : arg.end,        
                },
                success: function(data) {
                //called when successful
                console.log(data);
                // $('#ajaxphp-results').html(data);
                },
                error: function(e) {
                //called when there is an error
                //console.log(e.message);
                }
              });
      },
      eventClick: function(event) {
        console.log('click');
        console.log(event);
      // alert("Event ID: " + event.id + " Start Date: " + event.start + " End Date: " + event.end);
      },
      eventDrop: function( event, delta, revertFunc, jsEvent, ui, view ) {
        console.log('drop');
       },
      eventResize:  function( event, delta, revertFunc, jsEvent, ui, view ) {
        console.log('resize');
       },
    });

    calendar.render();

    // load the list of available timezones, build the <select> options
    // it's HIGHLY recommended to use a different library for network requests, not this internal util func
    // FullCalendar.requestJson('GET', 'php/get-time-zones.php', {}, function(timeZones) {

    //   timeZones.forEach(function(timeZone) {
    //     var optionEl;

    //     if (timeZone !== 'UTC') { // UTC is already in the list
    //       optionEl = document.createElement('option');
    //       optionEl.value = timeZone;
    //       optionEl.innerText = timeZone;
    //       timeZoneSelectorEl.appendChild(optionEl);
    //     }
    //   });
    // }, function() {
    //   // TODO: handle error
    // });

    // when the timezone selector changes, dynamically change the calendar option
    // timeZoneSelectorEl.addEventListener('change', function() {
    //   calendar.setOption('timeZone', this.value);
    // });
  });

</script>

@endpush