@props(['name'])

<svg
    {{ $attributes }}
    viewBox="0 0 1024 1024"
    fill="currentColor"
    xmlns="http://www.w3.org/2000/svg"
>
    {!! file_get_contents(resource_path("images/icons/{$name}.svg")) !!}
</svg>
