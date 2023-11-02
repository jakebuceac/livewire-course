<?php

namespace App\Livewire;

use App\Models\Post;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Rule;
use Livewire\Component;

class CreatePost extends Component
{
    #[Rule('required|min:5')]
    public string $title = '';

    #[Rule('required|min:5')]
    public string $body = '';

    public bool $success = false;

    public function render(): View
    {
        return view('livewire.create-post');
    }

    public function save(): void
    {
        $this->validate();

        Post::create([
            'title' => $this->title,
            'body' => $this->body,
        ]);

        $this->success = true;

        $this->reset('title', 'body');
    }
}
