@props(['name'])

<div x-data="{
    name: @js($name),
    show: false,
    showIfActive(active) { this.show = this.name === active; }
}" x-init="showIfActive(activeTab); $watch('activeTab', active => showIfActive(active))"
    x-cloak x-show="show" role="tabpanel" data-name="{{ $name }}"
    {{ $attributes }}>
    {{ $slot }}
</div>
