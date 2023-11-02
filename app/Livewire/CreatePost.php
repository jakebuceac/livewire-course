<?php

namespace App\Livewire;

use App\Livewire\Forms\PostForm;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
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

    public function mount(): void
    {
        // http://localhost/posts/create?post_id={post}
        $post = Post::find(request('post_id'));
        $this->form->post = $post;
        $this->form->title = $post->title;
        $this->form->body = $post->body;
    }

    public function validateTitle(): void
    {
        $this->validateOnly('form.title');
    }

    public function updatedFormTitle(): void
    {
        $this->form->title = Str::headline($this->form->title);
    }
}
