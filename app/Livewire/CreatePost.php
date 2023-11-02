<?php

namespace App\Livewire;

use App\Livewire\Forms\PostForm;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreatePost extends Component
{
    public PostForm $form;

    public bool $success = false;

    public function render(): View
    {
        return view('livewire.create-post');
    }

    public function save(): void
    {
        $this->validate();
        $this->form->save();
        $this->success = true;
    }

    public function validateTitle(): void
    {
        $this->validateOnly('form.title');
    }
}
