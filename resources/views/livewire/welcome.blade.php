<div>
    @if(Auth::check())
        <div>
            <h1>Welcome {{ Auth::user()->name }}</h1>
            <livewire:user.auth.logout />
        </div>
    @else
        <div>
            <h1>Welcome Guest</h1>
            <a href="{{ route('user.dashboard.show') }}" class="bg-amber-600 text-white">Dashboard</a>
        </div>
    @endif
</div>
