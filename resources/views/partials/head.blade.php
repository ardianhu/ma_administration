<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<title>{{ $title ?? 'PPMA' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

{{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
@php
$isProduction = app()->environment('production');
$manifestPath = $isProduction ? '../public_html/build/manifest.json' : public_path('build/manifest.json');
@endphp
@if ($isProduction && file_exists($manifestPath))
@php
$manifest = json_decode(file_get_contents($manifestPath), true);
@endphp
<link rel="stylesheet" href="{{ config('app.url') }}/build/{{ $manifest['resources/css/app.css']['file'] }}">
@else
@viteReactRefresh
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
@fluxAppearance
