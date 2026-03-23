<div class="relative" style="height:100vh">
    <div class="absolute top-2 right-2 z-50">
        <a href="{{ route('specialist.appointment.index') }}" class="btn btn-sm btn-error shadow-lg">
            <x-carbon-close class="w-4 h-4 mr-1" />
            Sair da sala
        </a>
    </div>
    <iframe
        src="{{ $embedUrl }}"
        style="width:100%;height:100%;border:0"
        allow="camera; microphone; fullscreen; display-capture; autoplay">
    </iframe>
</div>
