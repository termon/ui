@auth
    <form method="post" action="{{ route('logout') }}" class="flex gap-2 p-0 m-0">
        @csrf
        <x-ui.button  variant="none" >Logout</x-ui.button>
        <div class="py-1.5 text-gray-400 text-xs">{{ auth()?->user()?->email }} ({{ auth()?->user()?->role}})</div> 
    </form>
@endauth 
@guest
<div class="flex gap-2 items-center"> 
    <x-ui.nav.item href="{{route('login')}}">Login</x-ui.nav.item>
    <x-ui.nav.item href="{{route('register')}}">Register</x-ui.nav.item>
</div>
@endguest 


