<?php

namespace App\Livewire;

use App\Livewire\Forms\PostForm;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditPost extends Component
{
    public PostForm $form;

    public bool $success = false;

    public function render(): View
    {
        return view('livewire.edit-post');
    }

    public function mount(Post $post): void
    {
        $this->form->setPost($post);
    }

    public function update(): void
    {
        $this->validate();
        $this->form->update();
        $this->success = true;
    }

    public function validateTitle(): void
    {
        $this->validateOnly('form.title');
    }
}
