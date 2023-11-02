<?php

namespace App\Livewire\Forms;

use App\Models\Post;
use Livewire\Attributes\Rule;
use Livewire\Form;

class PostForm extends Form
{
    public ?Post $post;

    #[Rule('required|min:5')]
    public string $title = '';

    #[Rule('required|min:5')]
    public string $body = '';

    public function save(): void
    {
        Post::create($this->all());

        $this->reset('title', 'body');
    }

    public function update(): void
    {
        $this->post->update($this->all());
    }

    public function setPost(Post $post): void
    {
        $this->post = $post;
        $this->title = $post->title;
        $this->body = $post->body;
    }
}
