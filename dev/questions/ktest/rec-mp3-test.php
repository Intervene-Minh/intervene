<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>RECORD MP3 TEST Page</title>
        <style type='text/css'>
            ul { 
                list-style: none; 
            }
            #recordingslist audio { 
                display: block; margin-bottom: 10px; 
            }
        </style>
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    </head>
    <body>
        <div class="container">
            <h3>Kevin's mp3 test</h3>
            <button id="btnRecord" class="btn btn-danger">Record</button>
            <button id="btnStop" disabled class="btn btn-default">Stop</button>
            
        </div>
        <script src="//code.jquery.com/jquery-3.4.1.min.js" 
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous">
        </script>
    </body>
</html>