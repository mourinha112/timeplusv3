<?php

namespace App\Livewire\Specialist\VideoCall;

use App\Models\Room;
use App\Services\JitsiService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.videocall', ['title' => 'Videochamada'])]
class Show extends Component
{
    public Room $room;
    public string $displayName;
    public string $embedUrl;

    public function mount(string $code, JitsiService $jitsiService)
    {
        $this->room = Room::where('code', $code)->firstOrFail();

        if ($this->room->status !== 'open') {
            session()->flash('error', 'Esta sala foi encerrada. Verifique sua agenda.');
            $this->redirect(route('specialist.appointment.index'), navigate: true);
        }

        $specialist = auth('specialist')->user();
        $this->displayName = $specialist ? trim($specialist->name) : 'Especialista';
        $this->displayName = mb_substr($this->displayName, 0, 60);

        $this->embedUrl = $jitsiService->buildEmbedUrl($this->room->code, $this->displayName);
    }

    public function render()
    {
        return view('livewire.specialist.video-call.show');
    }
}