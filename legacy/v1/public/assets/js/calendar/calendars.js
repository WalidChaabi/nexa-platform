'use strict';

/* eslint-disable require-jsdoc, no-unused-vars */

var CalendarList = [];

function CalendarInfo() {
    this.id = null;
    this.name = null;
    this.checked = true;
    this.color = null;
    this.bgColor = null;
    this.borderColor = null;
    this.dragBgColor = null;
}

function addCalendar(calendar) {
    CalendarList.push(calendar);
}

function findCalendar(id) {
    var found;

    CalendarList.forEach(function(calendar) {
        if (calendar.id === id) {
            found = calendar;
        }
    });

    return found || CalendarList[0];
}

function hexToRGBA(hex) {
    var radix = 16;
    var r = parseInt(hex.slice(1, 3), radix),
        g = parseInt(hex.slice(3, 5), radix),
        b = parseInt(hex.slice(5, 7), radix),
        a = parseInt(hex.slice(7, 9), radix) / 255 || 1;
    var rgba = 'rgba(' + r + ', ' + g + ', ' + b + ', ' + a + ')';

    return rgba;
}

(function() {
    var calendar;
    $.ajax({
        type:'GET',
        url:'/employe/events/1',
        data:'_token = <?php echo csrf_token() ?>',
        success:function(data) {
          data.map(item => {
            calendar = new CalendarInfo();
            calendar.id = String(item['id']);
            calendar.name = item['name'];
            calendar.color = item['color'];
            calendar.bgColor = item['bgColor'];
            calendar.dragBgColor =item['dragBgColor'];
            calendar.borderColor =item['borderColor'];
            addCalendar(calendar);
          })
        }
     });
})();
