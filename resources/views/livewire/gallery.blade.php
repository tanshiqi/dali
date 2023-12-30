<div class="mx-auto max-w-7xl px-4 py-6" id="lightgallery">

    @foreach ($gallery as $date => $items)
        <div class="mb-2 flex items-center justify-between font-bold text-white">
            <h3 class="">{{ $date }}</h3>
            <div class="text-sm">{{ count($items) . ' 件作品' }}</div>
        </div>
        <div class="mb-4 grid grid-cols-4 gap-0.5 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10" wire:poll.30s>
            @foreach ($items as $photo)
                <a href="{{ Storage::disk('qiniu')->url($photo->result) }}" title="{{ $photo->prompt }}">
                    <img class="" src="{{ Storage::disk('qiniu')->url($photo->result) }}?imageView2/1/w/300/format/jpg">
                </a>
            @endforeach
        </div>
    @endforeach

</div>

@push('styles')
    <link href="https://testingcf.jsdelivr.net/npm/lightgallery@2/css/lightgallery-bundle.min.css " rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://testingcf.jsdelivr.net/npm/lightgallery@2/lightgallery.min.js"></script>
@endpush

<script>
    window.lightGallery(document.getElementById("lightgallery"), {
        selector: "a",
        // download: false,
    });
</script>
