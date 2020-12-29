<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div>
    <h1>
       Các case còn thiếu
    </h1>
    <div>
        @foreach($code_missing as $code)
            <span style="background: #9bffba; padding: 2px 5px; margin-right: 5px; margin-bottom: 5px; display: inline-block">{{ $code }}</span>
        @endforeach
    </div>
</div>

<div>
    <h1>
        Các case chưa có nơi ở hiện tại (không lấy được từ hệ thống cũ)
    </h1>
    <div>
        @foreach($missing_place as $code)
            <span style="background: #9bffba; padding: 2px 5px; margin-right: 5px; margin-bottom: 5px; display: inline-block">{{ $code }}</span>
        @endforeach
    </div>
</div>

<div>
    <h1>
        Các case đang có loài khác (nên check lại)
    </h1>
    <div>
        @foreach($type_other as $code)
            <span style="background: #9bffba; padding: 2px 5px; margin-right: 5px; margin-bottom: 5px; display: inline-block">{{ $code }}</span>
        @endforeach
    </div>
</div>
</body>
</html>
