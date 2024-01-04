<div>
    @isset($jsPath)
        <script>
            {!! file_get_contents($jsPath) !!}
        </script>
    @endisset
    @isset($cssPath)
        <style>
            {!! file_get_contents($cssPath) !!}
        </style>
    @endisset

    <div
         class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;" x-data="LivewireUIModal()" x-on:close.stop="setShowPropertyTo(false)"
         x-on:keydown.escape.window="closeModalOnEscape()" x-show="show">
        <div class="h-dvh w-dvw flex items-center justify-center p-4 text-center sm:p-10">
            <div
                 class="fixed inset-0 transform transition-all duration-300 ease-in-out" x-show="show" x-on:click="closeModalOnClickAway()"
                 x-transition.opacity.duration.300ms>
                <div class="absolute inset-0 bg-gray-800 opacity-75"></div>
            </div>

            <div
                 class="relative block h-full w-full transform overflow-hidden rounded-xl bg-gray-950 text-left align-bottom shadow-xl transition-all ease-in-out sm:align-middle"
                 id="modal-container" aria-modal="true" x-show="show && showActiveComponent" x-transition.duration.300ms
                 x-trap.noscroll.inert="show && showActiveComponent">
                @forelse($components as $id => $component)
                    <div x-show.immediate="activeComponent == '{{ $id }}'" x-ref="{{ $id }}" wire:key="{{ $id }}">
                        @livewire($component['name'], $component['arguments'], key($id))
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
