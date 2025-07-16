<section>
    <h3 class="text-xl">Encontramos <strong>{{ $this->specialists->count() }} especialistas</strong> disponíveis para você.</h3>

    @foreach($this->specialists as $specialist)

    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
        <li class="text-danger">{{ $error }}</li>
        @endforeach
    </ul>
    @endif

    <div class="bg-gray-300/10 grid grid-cols-1 md:grid-cols-2 rounded-lg my-5">
        <livewire:user.specialist.card :specialist="$specialist" />
        <livewire:user.specialist.schedule :specialist="$specialist" />
    </div>
    @endforeach
</section>
