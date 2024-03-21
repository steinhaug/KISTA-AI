
const myToast  = document.getElementById('myToast');
const myToasts = document.getElementById('myToasts');
const myAlert  = document.getElementById('myAlert');
const myAlerts  = $('#page .page-content');

function toastit_warning(title, subtitle, body){
    quickToast('ADVARSEL', title, subtitle, body);
}

function quickAlert(alertType, title, body){

    let svgico;
    let bgcol;
    switch (alertType) {
        case 'alert':
        case 'error':
            svgico = '<use xlink:href="#exclamation-triangle-fill"/>';
            bgcol  = 'bg-red-dark';
            break;
        case 'warning':
            svgico = '<use xlink:href="#exclamation-triangle-fill"/>';
            bgcol  = 'bg-yellow-dark';
            break;
        case 'success':
            svgico = '<use xlink:href="#check-circle-fill"/>';
            bgcol  = 'bg-green-dark';
            break;
        default:
            svgico = '<use xlink:href="#info-fill"/>';
            bgcol  = 'bg-blue-dark';
    }

    let newAlert = $(myAlert).clone().removeAttr("id").prependTo(myAlerts);

    $(newAlert).addClass(bgcol).find('svg').attr('aria-label',alertType).html(svgico).parent().next().html(title).after('<div class="body">' + body + '</div>');
    $(newAlert).show();
}


function quickToast(toastType, ...params) {

    let title;
    let subtitle;
    let body;
    if( params.length === 3){
        title = params[0];
        subtitle = params[1];
        body = params[2];
        if(isEmpty(subtitle))
            subtitle = getTimeHourString();
    } else {
        title = params[0];
        subtitle = getTimeHourString();
        body = params[1];
    }

    let hex;
    let delay;
    switch (toastType) {
        case 'alert':
        case 'error':
            delay = 0;
            hex = '#ff0000';
            break;
        case 'warning':
            delay = 5000;
            hex = '#ffff44';
            break;
        case 'success':
            delay = 2500;
            hex = '#239d40';
            break;
        default:
            delay = 2500;
            hex = '#1873bc';
    }

    let newToast = $(myToast).clone().removeAttr("id").appendTo(myToasts);
    let newDelay = $(newToast).data('menu-hide') ?? delay;
    $(newToast).find('.toast-header svg rect').attr('fill', hex).parent().next().html(title).next().html(subtitle).parent().next().html(body);

    if(newDelay)
        var notificationToast = new bootstrap.Toast(newToast, {'autohide': true, 'delay': newDelay});
        else
        var notificationToast = new bootstrap.Toast(newToast, {'autohide': false});

    notificationToast.show();
}

function getTimeHourString() {
    const now = new Date();
    let hours = now.getHours().toString();
    let minutes = now.getMinutes().toString();

    // Pad with leading zeros if necessary
    if (hours.length < 2) {
        hours = '0' + hours;
    }
    if (minutes.length < 2) {
        minutes = '0' + minutes;
    }

    return `${hours}:${minutes}`;
}