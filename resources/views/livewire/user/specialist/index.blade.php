<section class="px-4 sm:px-6 lg:px-8">
    <div class="flex flex-row space-x-2 mb-4">
        <h2 class="text-base-content">Encontramos <strong>{{ $this->specialists->count() }}</strong> especialistas disponíveis para você.</h2>
    </div>

    @foreach($this->specialists as $specialist)

    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li class="text-danger">{{ $error }}</li>
        @endforeach
    </ul>
    @endif

     <div class="grid md:grid-cols-2 grid-cols-1 gap-4 mb-3">
        <livewire:user.specialist.card :specialist="$specialist" />
        <livewire:user.specialist.schedule :specialist="$specialist" />
    </div>
    @endforeach
</section>
