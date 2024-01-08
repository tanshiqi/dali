<div class="min-h-dvh bg-gray-950">
    <div class="flex h-12 items-center justify-center border-b border-gray-800 bg-gray-900 text-lg font-medium text-gray-200 lg:h-16">
        <svg class="h-8" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
            <path id="b"
                  d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
        </svg>
    </div>
    <div class="px-5 pt-6 lg:px-6">
        <div class="w-full" id="album-container">
            @foreach ($items as $item)
                <img class="cursor-pointer" src="{{ Storage::disk('qiniu')->url($item->result) }}?imageView2/2/w/500/format/jpg"
                     wire:click.stop="$dispatch('openModal', { component: 'viewer', arguments: { item: {{ $item->makeHidden(['updated_at']) }} }})">
            @endforeach
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/macy@2"></script>
<script>
    var macy = Macy({
        container: '#album-container',
        trueOrder: false,
        waitForImages: false,
        margin: 10,
        columns: 4,
        breakAt: {
            1536: 3,
            1024: 2
        }
    });
</script>
