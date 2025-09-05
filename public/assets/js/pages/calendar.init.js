// Fetch reservations from API
async function fetchReservations() {
    try {
        const response = await fetch('/api/reservations/calendar');
        const reservations = await response.json();
        return reservations;
    } catch (error) {
        console.error('Error fetching reservations:', error);
        return [];
    }
}

// Map time_slot to start and end times
function mapTimeSlotToTimes(timeSlot) {
    switch (timeSlot) {
        case 'morning':
            return { startTime: '09:00', endTime: '12:00' };
        case 'afternoon':
            return { startTime: '12:00', endTime: '17:00' };
        case 'evening':
            return { startTime: '17:00', endTime: '22:00' };
        default:
            return { startTime: null, endTime: null };
    }
}

// Transform API reservations to FullCalendar events
function transformReservationsToEvents(reservations) {
    return reservations.map(reservation => {
        const { startTime, endTime } = mapTimeSlotToTimes(reservation.time_slot);
        const eventDate = reservation.event_date;
        const isAllDay = reservation.allDay;

        return {
            id: reservation.id,
            title: reservation.title,
            start: startTime ? `${eventDate}T${startTime}` : eventDate,
            end: endTime ? `${eventDate}T${endTime}` : null,
            allDay: isAllDay,
            className: reservation.className,
            description: reservation.description,
            location: reservation.location,
            extendedProps: {
                booked_by: reservation.booked_by,
                time_slot: reservation.time_slot,
                hall: reservation.hall
            }
        };
    });
}

function upcomingEvent(e) {
    e.sort(function (e, t) { return new Date(e.start) - new Date(t.start); });
    document.getElementById("upcoming-event-list").innerHTML = null;
    Array.from(e).forEach(function (e) {
        var t = e.title;
        var startDate = e.start ? str_dt(e.start) : null;
        var classNames = e.className.split("-");
        var i = e.description || "";
        var timeSlot = (e.extendedProps.time_slot).toUpperCase() || "N/A";
        var bookedBy = e.extendedProps.booked_by || "N/A";
        var hall = e.extendedProps.hall || "N/A";
        var u_event = `
            <div class='card mb-3'>
                <div class='card-body'>
                    <div class='d-flex mb-3'>
                        <div class='flex-grow-1'><i class='mdi mdi-checkbox-blank-circle me-2 text-${classNames[1]}'></i><span class='fw-medium'>${startDate}</span></div>
                        <div class='flex-shrink-0'><small class='badge bg-primary-subtle text-primary ms-auto'>${timeSlot}</small></div>
                    </div>
                    <h6 class='card-title fs-16'>${t} - ${bookedBy}</h6>
                    <p class='text-muted mb-0'>${e.location}, ${hall} (${i})</p>
                </div>
            </div>`;
        document.getElementById("upcoming-event-list").innerHTML += u_event;
    });
}

var str_dt = function (e) {
    var e = new Date(e),
        t = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"][e.getMonth()],
        n = "" + e.getDate(),
        e = e.getFullYear();
    if (t.length < 2) t = "0" + t;
    if (n.length < 2) n = "0" + n;
    return [n + " " + t, e].join(",");
};

document.addEventListener("DOMContentLoaded", async function () {
    var reservations = await fetchReservations();
    var y = transformReservationsToEvents(reservations);

    var a = FullCalendar.Draggable;
    var d = document.getElementById("external-events");

    new a(d, {
        itemSelector: ".external-event",
        eventData: function (e) {
            return {
                id: Math.floor(11000 * Math.random()),
                title: e.innerText,
                allDay: true,
                start: new Date(),
                className: e.getAttribute("data-class")
            };
        }
    });

    function r() {
        return window.innerWidth >= 768 && window.innerWidth < 1200 ? "timeGridWeek" : window.innerWidth <= 768 ? "listMonth" : "dayGridMonth";
    }

    var calendarEl = document.getElementById("calendar");
    var b = new FullCalendar.Calendar(calendarEl, {
        timeZone: "local",
        editable: true,
        droppable: true,
        selectable: true,
        navLinks: true,
        initialView: r(),
        themeSystem: "bootstrap",
        headerToolbar: {
            left: "prev,next today",
            center: "title",
            right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
        },
        windowResize: function (e) {
            var t = r();
            b.changeView(t);
        },
        eventResize: function (t) {
            var e = y.findIndex(function (e) { return e.id == t.event.id; });
            if (y[e]) {
                y[e].title = t.event.title;
                y[e].start = t.event.start;
                y[e].end = t.event.end || null;
                y[e].allDay = t.event.allDay;
                y[e].className = t.event.classNames[0];
                y[e].description = t.event._def.extendedProps.description || "";
                y[e].location = t.event._def.extendedProps.location || "";
            }
            upcomingEvent(y);
        },
        eventClick: function (e) {
            window.location.href = `/admin/reservation/view/${e.event.id}`;
        },
        // dateClick: function (e) {
        //     window.location.href = `/admin/reservation/add?date=${e.dateStr}`;
        // },
        events: y,
        eventReceive: function (e) {
            var newEvent = {
                id: parseInt(e.event.id),
                title: e.event.title,
                start: e.event.start,
                allDay: e.event.allDay,
                className: e.event.classNames[0]
            };
            y.push(newEvent);
            upcomingEvent(y);
        },
        eventDrop: function (t) {
            var e = y.findIndex(function (e) { return e.id == t.event.id; });
            if (y[e]) {
                y[e].title = t.event.title;
                y[e].start = t.event.start;
                y[e].end = t.event.end || null;
                y[e].allDay = t.event.allDay;
                y[e].className = t.event.classNames[0];
                y[e].description = t.event._def.extendedProps.description || "";
                y[e].location = t.event._def.extendedProps.location || "";
            }
            upcomingEvent(y);
        }
    });

    b.render();
    upcomingEvent(y);

    document.getElementById("btn-new-event").addEventListener("click", function (e) {
        window.location.href = `/admin/reservation/add`;
    });
});