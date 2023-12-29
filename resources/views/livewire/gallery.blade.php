<div class="mx-auto max-w-7xl px-4 py-6">
    <div class="grid grid-cols-4 gap-0.5 sm:grid-cols-6 md:grid-cols-8 lg:grid-cols-10" id="lightgallery">
        @foreach ($gallery as $photo)
            <a href="{{ Storage::disk('qiniu')->url($photo->result) }}" title="{{ $photo->prompt }}">
                <img class="" src="{{ Storage::disk('qiniu')->url($photo->result) }}?imageView2/1/w/300/format/jpg" alt="">
            </a>
        @endforeach
    </div>
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
