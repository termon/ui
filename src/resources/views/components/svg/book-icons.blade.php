@props([
    'icon' => 'default',
    'class' => ''
])
@if (str_contains($icon, 'background'))
    <svg {{ $attributes->merge(["class" => "w-6 h-6"]) }}  xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M17 8H7c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-4 12.5h-2V19h2v1.5zm0-2.5h-2c0-1.5-2.5-3-2.5-5c0-1.93 1.57-3.5 3.5-3.5s3.5 1.57 3.5 3.5c0 2-2.5 3.5-2.5 5zm5-11.5H6C6 5.67 6.67 5 7.5 5h9c.83 0 1.5.67 1.5 1.5zm-1-3H7C7 2.67 7.67 2 8.5 2h7c.83 0 1.5.67 1.5 1.5z"/></svg>
@elseif (str_contains($icon, 'introduction'))
    <svg {{ $attributes->merge(["class" => "w-6 h-6"]) }} xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M14.59 7.41L18.17 11H6v2h12.17l-3.59 3.59L16 18l6-6l-6-6l-1.41 1.41zM2 6v12h2V6H2z"/></svg>
@elseif (str_contains($icon, 'preparation'))
    <svg {{ $attributes->merge(["class" => "w-6 h-6"]) }} xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="m16.99 5l.63 1.37l1.37.63l-1.37.63L16.99 9l-.63-1.37L14.99 7l1.37-.63l.63-1.37M11 6.13V4h2c.57 0 1.1.17 1.55.45l1.43-1.43A4.899 4.899 0 0 0 13 2H7.5v2H9v2.14A5.007 5.007 0 0 0 5.26 9.5h3.98L15 11.65v-.62a5 5 0 0 0-4-4.9zM1 22h4V11H1v11zm19-5h-7l-2.09-.73l.33-.94L13 16h2.82c.65 0 1.18-.53 1.18-1.18c0-.49-.31-.93-.77-1.11L8.97 11H7v9.02L14 22l8-3c-.01-1.1-.89-2-2-2zm0-3c1.1 0 2-.9 2-2s-2-4-2-4s-2 2.9-2 4s.9 2 2 2z"/></svg>
@elseif (str_contains($icon, 'placement'))
    <svg {{ $attributes->merge(["class" => "w-6 h-6"]) }} xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M4 19V8h16v3.29c.72.22 1.4.54 2 .97V8c0-1.11-.89-2-2-2h-4V4c0-1.11-.89-2-2-2h-4c-1.11 0-2 .89-2 2v2H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h7.68c-.3-.62-.5-1.29-.6-2H4zm6-15h4v2h-4V4z"/><path fill="currentColor" d="M18 13c-2.76 0-5 2.24-5 5s2.24 5 5 5s5-2.24 5-5s-2.24-5-5-5zm1.65 7.35L17.5 18.2V15h1v2.79l1.85 1.85l-.7.71z"/></svg>
@elseif (str_contains($icon, 'student'))
    <svg {{ $attributes->merge(["class" => "w-6 h-6"]) }} xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 256 256"><path fill="currentColor" d="m226.5 56.4l-96-32a8.5 8.5 0 0 0-5 0l-95.9 32h-.2l-1 .5h-.1l-1 .6c0 .1-.1.1-.2.2l-.8.7l-.7.8c0 .1-.1.1-.1.2l-.6.9c0 .1 0 .1-.1.2l-.4.9l-.3 1.1v.3A3.7 3.7 0 0 0 24 64v80a8 8 0 0 0 16 0V75.1l33.6 11.2A63.2 63.2 0 0 0 64 120a64 64 0 0 0 30 54.2a96.1 96.1 0 0 0-46.5 37.4a8.1 8.1 0 0 0 2.4 11.1a7.9 7.9 0 0 0 11-2.3a80 80 0 0 1 134.2 0a8 8 0 0 0 6.7 3.6a7.5 7.5 0 0 0 4.3-1.3a8.1 8.1 0 0 0 2.4-11.1a96.1 96.1 0 0 0-46.5-37.4a64 64 0 0 0 30-54.2a63.2 63.2 0 0 0-9.6-33.7l44.1-14.7a8 8 0 0 0 0-15.2ZM128 168a48 48 0 0 1-48-48a48.6 48.6 0 0 1 9.3-28.5l36.2 12.1a8 8 0 0 0 5 0l36.2-12.1A48.6 48.6 0 0 1 176 120a48 48 0 0 1-48 48Z"/></svg>
@elseif (str_contains($icon, 'academic'))
    <svg {{ $attributes->merge(["class" => "w-6 h-6"]) }} xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><path fill="currentColor" d="M20 17a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H9.46c.35.61.54 1.3.54 2h10v11h-9v2m4-10v2H9v13H7v-6H5v6H3v-8H1.5V9a2 2 0 0 1 2-2H15M8 4a2 2 0 0 1-2 2a2 2 0 0 1-2-2a2 2 0 0 1 2-2a2 2 0 0 1 2 2Z"/></svg>
@else
    <!-- default page -->
    <svg class="w-6 h-6 {{$class}}" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" fill="none"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
@endif
