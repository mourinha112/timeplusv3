<?php

namespace App\Livewire\User\Specialist;

use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;

class Card extends Component
{
    public $specialist;
    public bool $favorited = false;

    public function mount()
    {
        $this->favorited = Favorite::where('specialist_id', $this->specialist->id)
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function favorite()
    {
        if ($this->favorited) {
            Favorite::where('specialist_id', $this->specialist->id)
                ->where('user_id', Auth::id())
                ->delete();

            $this->favorited = false;
            LivewireAlert::title('Sucesso!')
                ->text('Especialista removido dos favoritos com sucesso!')
                ->success()
                ->show();
            return;
        } else {
            Favorite::create([
                'user_id' => Auth::id(),
                'specialist_id' => $this->specialist->id,
            ]);
            $this->favorited = true;
            LivewireAlert::title('Sucesso!')
                ->text('Especialista adicionado aos favoritos com sucesso!')
                ->success()
                ->show();
            return;
        }
    }

    public function render()
    {
        return view('livewire.user.specialist.card');
    }
}
