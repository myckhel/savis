<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no" />
  @viteReactRefresh
  @vite('resources/js/app.jsx')
  @routes
  @inertiaHead
</head>

<body>
  @inertia
</body>

</html>
