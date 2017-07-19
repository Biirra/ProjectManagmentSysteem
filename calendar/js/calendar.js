/**
 * Created by Administrator on 16-1-2017.
 */
function fillCalendar(data, itemValues) {

    $.getScript('calendar/js/fullcalendar.min.js', function () {
        $('#calendar').empty();

        // kalender snippet
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            editable: false,
            events: getAllEvents(data, itemValues)
        });
        // einde kalender snippet
    });
}

function getAllEvents(data, itemValues) {
    return getOpdrachtEvents(data, itemValues);
}

function getOpdrachtEvents(data, itemValues) {
    var events = [];
    for (var i = 0; i < data.length; i++) {
        for(var j = 0; j < data.length; j++){
            if (data[i]['opdracht-id'] == itemValues[j]) {
                events.push({
                    id: data[i]['opdracht-id'],
                    title: data[i]['opdracht-naam'],
                    start: data[i]['datum-aangemaakt'],
                    end: data[i]['werkelijke-oplever-datum'],
                    url: "opdrachten.php?state=opdracht-info&id=" + data[i]['opdracht-id']
                });
            }
        }
    }
    return events;
}
