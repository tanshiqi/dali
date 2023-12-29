<div class="mx-auto max-w-7xl px-4 py-6">
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
        @foreach ($gallery as $photo)
            <img class="rounded-md" src="{{ Storage::disk('qiniu')->url($photo->result) }}?imageView2/1/w/300/format/jpg" alt="">
        @endforeach
    </div>
</div>
