<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <title>Check for holidays</title>
</head>
<body>
<div class="container w-50 mt-5 text-center">
    <div class="date text-center">
        @if (isset($error))
            <div class="alert alert-danger" role="alert">

            </div>
        @endif
        <h2 class="mb-4"> Enter date to check it for holidays!</h2>
        <form class="date-form" action="" method="post">
            @csrf
            <input class="form-control" type="text" name="date" id="date" placeholder="For example, 01.01.2000" required>
            <small class="form-text text-danger">{{ $errors->first('date')}}</small>
            <button class="btn btn-dark pl-4 pr-4 mt-3" type="submit" id="submit">Submit</button>
        </form>
    </div>
    @if(session()->has('response'))
        <div class="response mt-3">
            <p> {{ session()->get('response') }}</p>
        </div>
    @endif
</div>
</body>
</html>