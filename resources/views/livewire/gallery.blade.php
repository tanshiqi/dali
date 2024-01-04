<div class="mx-auto max-w-7xl px-4 py-6">
    @foreach ($taskgroups as $date => $tasks)
        <div class="mb-2 flex items-center justify-between font-bold">
            <h3 class="">{{ $date }}</h3>
            <div class="text-sm">{{ count($tasks) . ' 件作品' }}</div>
        </div>
        <div class="mb-4 grid grid-cols-4 gap-0.5 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10" wire:poll.30s>
            @foreach ($tasks as $task)
                <div class="cursor-pointer" wire:click.stop="$dispatch('openModal', { component: 'viewer', arguments: { task: {{ $task->id }} }})">
                    <img src="{{ Storage::disk('qiniu')->url($task->result) }}?imageView2/1/w/300/format/jpg" title="{{ $task->prompt }}">
                </div>
            @endforeach
        </div>
    @endforeach
</div>
