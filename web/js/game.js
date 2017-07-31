$(document).on('click','span', function(){
    console.log($(this).text());
    if ($(this).text() === " " && $('#turn').attr('value') == true) {
        var id = $(this).attr('id');
        var row = id.split('_')[0];
        var column = id.split('_')[1];
        var json = {row : row, column : column};
        $.ajax({
            type: 'post',
            url:  '/game/move',
            data: json,
            dataType    : 'text',
            success: function (data, status, object) {
                if (data === 'success') {
                    console.log('move_success');
                    window.location.href = '/game';
                } else if (data === 'win') {
                    $('#message').html('You have won!');
                    $('#message').toggleClass( "green");
                    setTimeout(function() {
                        window.location.href = '/';
                    }, 2000)
                } else if (data === 'draw') {
                    $('#message').html('Draw');
                    $('#message').toggleClass( "yellow");
                    setTimeout(function() {
                        window.location.href = '/';
                    }, 2000)
                } else {
                    console.log(data);
                }
            }
        });

    }
});

function check(){
    $.ajax({
        type: 'get',
        url: '/game/check',
        dataType: 'text',
        success: function (data, status, object) {
            if (data === 'none') {
                window.location.href = '/game';
            } else if (data === 'defeat') {
                $('#message').html('You have lost!');
                $('#message').toggleClass( "red");
                setTimeout(function() {
                        window.location.href = '/';
                    }, 2000)
            } else if (data === 'draw') {
                $('#message').html('Draw');
                $('#message').toggleClass( "yellow");
                setTimeout(function() {
                    window.location.href = '/';
                }, 2000)
            } else {
                console.log(data);
            }
        }
    });
}

$(document).ready(function () {
    if ($('#turn').attr('value') == false) {
        setTimeout(function() {
            check();
        }, 2000)
    }
});