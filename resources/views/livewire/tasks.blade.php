<?php

use App\Models\Task;
use function Livewire\Volt\{state, computed};

state(['title' => '']);

$addTask = function () {
    $this->validate([
        'title' => 'required|string|max:255',
    ]);

    Task::create([
        'title'     => $this->title,
        'completed' => false,
    ]);

    $this->reset('title');
};

$toggle = function (int $id) {
    $task = Task::findOrFail($id);
    $task->update(['completed' => !$task->completed]);
};

$tasks = computed(function () {
    return Task::latest()->get();
});

?>

<div>
    <form wire:submit="addTask">
        <input
            type="text"
            wire:model="title"
            placeholder="New task title"
        />
        <button type="submit">Add Task</button>
    </form>

    @error('title')
        <span>{{ $message }}</span>
    @enderror

    <ul>
        @foreach ($this->tasks as $task)
            <li>
                <button wire:click="toggle({{ $task->id }})">
                    {{ $task->completed ? '✓' : '○' }}
                </button>
                <span style="{{ $task->completed ? 'text-decoration: line-through' : '' }}">
                    {{ $task->title }}
                </span>
            </li>
        @endforeach
    </ul>
</div>
