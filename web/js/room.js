function check(){
    $.ajax({
        type: 'get',
        url: '/room/check',
        dataType: 'text',
        success: function (data, status, object) {
            if (data === 'game') {
                window.location.href = '/game';
            } else if (data === 'wait') {
                window.location.href = '/room';
            } else if (data === 'error') {
                window.location.href = '/';
            }
        }
    });
}

$(document).ready(function () {
    setTimeout(function () {
        check();
    }, 2000)
});

