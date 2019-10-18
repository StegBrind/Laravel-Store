const xhr = new XMLHttpRequest();

$('#send_start').on('click', function ()
{
    if (CKEDITOR.instances.message_text.getData().trim() == "")
    {
        $('#show_info_message').css("color", "red");
        document.getElementById('show_info_message').innerText = "Текст сообщения должен быть обязательно заполнен.";
        $('h4').show();
        return;
    }
    xhr.open("POST", "send-notifications");
    xhr.setRequestHeader('X-CSRF-TOKEN', $('head > meta:nth-child(3)').attr('content'));
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onloadend = function ()
    {
        $('#show_info_message').css("color", "green");
        document.getElementById('show_info_message').innerHTML = "Отправка писем началась.";
        setTimeout(function ()
        {
            $('h4').hide();
        }, 4000);
    };
    xhr.send("subject=" + document.getElementById('subject').value + "&message_text=" + CKEDITOR.instances.message_text.getData()
        + "&emails_confirmed=" + document.getElementById('emails_confirmed').checked);
    $('h4').show();
    $('#mail_gif').show();
});

