
document.addEventListener('DOMContentLoaded', function(){

    initDatePickers();

})

function initDatePickers() {

    const inputPopupEls = document.querySelectorAll('.date-picker-container input');

    inputPopupEls.forEach(el => {

        const div = document.createElement('div');
        div.classList.add('visually-hidden', 'calendar-datepicker-popup');

        el.insertAdjacentElement("afterend", div);
        el.readOnly = true;

        const calendarDatepickerPopup = new VanillaCalendar(div, {
            actions: {
                clickDay(event, dates) {
                    if (dates[0]) {
                        el.classList.remove('input_focus');
                        div.classList.add('visually-hidden');
                    }
                    el.value = dates;
                },
            },
        });
        calendarDatepickerPopup.init();

        el.addEventListener('click', (e) => {

            if(!el.classList.contains('input_focus')){
                el.classList.add('input_focus');
                div.classList.remove('visually-hidden');
            }else{
                el.classList.remove('input_focus');
                div.classList.add('visually-hidden');
            }

        }, {capture: true});

    });

    document.addEventListener('click', (e) => {

        const calendar = e.target.closest('.date-picker-container .calendar-datepicker-popup');

        if(!calendar){
            document
                .querySelectorAll('.date-picker-container .calendar-datepicker-popup:not(.visually-hidden)')
                .forEach(d => d.classList.add('visually-hidden'));
        }

    }, {capture: true});

}

const apiTravioSearchHotels = async function(fromDate, toDate) {

    const response = await fetch('/api/travio/search/hotels', {
        method: 'POST',
        body: JSON.stringify({
            from: fromDate,
            to: toDate
        }),
        headers: {
            'Content-Type': 'application/json; charset=UTF-8'
        }
    })

    if(response.ok){
        return response.json();
    }else{
        alert("Cannot retrieve hotels info");
    }
}