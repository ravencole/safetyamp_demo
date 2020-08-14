const mix = require('laravel-mix')

require('laravel-mix-tailwind')

mix.js('resources/js/main.js', 'public/js/app.js')
  .sass('resources/sass/app.scss', 'public/css/app.css')
  .tailwind('tailwind.config.js')

mix.disableNotifications()
