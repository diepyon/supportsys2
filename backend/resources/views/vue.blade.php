<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    
    <title>Example</title>
<link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body onfocus="top.focus()">
    <div id="app">
        <App />
    </div>

<script src="{{ mix('js/app.js') }}"></script> 
</body>
</html>

