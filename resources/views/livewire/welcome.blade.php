<div>
    @if(Auth::check())
        <div>
            <h1>Welcome {{ Auth::user()->name }}</h1>
        </div>
    @else
        <div>
            <h1>Welcome Guest</h1>
        </div>
    @endif
</div>
