<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $reservation->subject }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px;">
    {!! $content !!}
    <img src="{{ $trackingPixel }}" alt="" style="display:none;" width="1" height="1">
</body>
</html>