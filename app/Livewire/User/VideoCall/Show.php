<?php

namespace App\Livewire\User\VideoCall;

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
            session()->flash('error', 'Esta sala foi encerrada. Verifique se há sessão ativa.');
            $this->redirect(route('user.appointment.index'), navigate: true);
        }

        $user              = auth('user')->user();
        $this->displayName = $user ? trim($user->name) : 'Convidado';
        $this->displayName = mb_substr($this->displayName, 0, 60);

        $this->embedUrl = $jitsiService->buildEmbedUrl($this->room->code, $this->displayName);
    }

    public function render()
    {
        return view('livewire.user.video-call.show');
    }
}
