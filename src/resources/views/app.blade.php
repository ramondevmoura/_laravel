<!DOCTYPE html>
<html lang="pt-BR">
  <head>
    <meta charset="UTF-8" />
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>Sistema crud base</title>
    <link rel="icon" href="" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />

      @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    @inertiaHead
  </head>
  <body class="dark:bg-gray-900">
    @inertia
  </body>
</html>
