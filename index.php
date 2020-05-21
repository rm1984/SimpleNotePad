<?php
$file = 'note.txt';

if ($_SERVER['REQUEST_METHOD'] === 'POST')
{
    if (isset($_POST['text']) && !empty($_POST['text']))
    {
        $data = $_POST['text'];

        file_put_contents($file, nl2br($data)) ;
    }
}
else
{
    $data =  '';

    if (file_exists($file))
    {
        $data = file_get_contents($file);
    }
?>
<!doctype html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="UTF-8"/>
        <meta name="author" content="Riccardo Mollo"/>
        <meta name="description" content="SimpleNotePad"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="default-style" content="css/style.css"/>
        <meta http-equiv="cache-control" content="No-Cache"/>
        <link rel="icon" type="image/png" href="img/favicon-16x16.png" sizes="16x16"/>
        <link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32"/>
        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inconsolata" crossorigin="anonymous"/>
        <link rel="stylesheet" type="text/css" href="css/style.css" media="screen"/>
        <title>SimpleNotePad</title>
        <script>
window.onload = function prepare()
{
    note = document.getElementById('note');

    if (note.innerText && note.innerText !== "")
    {
        text_b64dec = atob(note.innerText);
        note.innerText = text_b64dec;
    }

    if (window.addEventListener)
    {
        note.addEventListener('DOMSubtreeModified', contentChanged, false);
    }
    else if (window.attachEvent)
    {
        note.attachEvent('DOMSubtreeModified', contentChanged);
    }

    function contentChanged()
    {
        save_data(btoa(note.innerText));
    }
};

function save_data(data)
{
    data = 'text=' + data;

    xhr = new XMLHttpRequest();
    xhr.open('POST', 'index.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function()
    {
        if (xhr.status !== 200 && xhr.status !== 0)
        {
//            alert('Error! Server responded with HTTP code ' + xhr.status + '.');

            note = document.getElementById('note');
            note.style.backgroundColor = '#ff2800';

            // if error occurs, blink red for half a second
            setTimeout(function()
            {
                note.style.backgroundColor = '#dedede';
            }, 500);

            return;
        }
    };

    xhr.send(data);
}

function reset()
{
    document.getElementById('note').innerText = '';
}
        </script>
    </head>
    <body>
        <div id="note" contenteditable="true"><?php echo $data; ?></div>
        <button id="reset" class="pure-material-button-contained" onclick="reset();">Reset</button>
    </body>
</html>
<?php
}
?>
