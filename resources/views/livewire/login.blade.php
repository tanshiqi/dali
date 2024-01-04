<div class="min-h-dvh flex flex-col justify-center bg-gray-700 px-6 py-12 pb-52 lg:px-8" x-data="loginpin"
     @setpin.window="$wire.pinCompleted($event.detail)">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <div class="flex justify-center text-center text-gray-100">
            <svg class="h-8" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
                <path id="b"
                      d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
            </svg>
        </div>
        <h2 class="mt-10 text-center text-lg font-bold leading-9 tracking-widest text-gray-300">请输入授权码</h2>
    </div>

    <div class="mx-auto mt-4 w-full max-w-sm">
        <form class="space-y-6" action="#" method="POST">
            <div>
                <div class="*:block *:px-0 *:w-1/6 *:mx-1 *:aspect-square *:rounded-md *:border-0 *:bg-white/5 *:text-center *:text-2xl *:font-bold *:text-white *:ring-0 focus:*:ring-0 disabled:*:pointer-events-none disabled:*:opacity-50 flex justify-between"
                     id="pin" data-hs-pin-input='{"availableCharsRE": "^[0-9]+$"}'>
                    <input data-hs-pin-input-item type="number" autofocus :disabled="$wire.lockPin">
                    <input data-hs-pin-input-item type="number" :disabled="$wire.lockPin">
                    <input data-hs-pin-input-item type="number" :disabled="$wire.lockPin">
                    <input data-hs-pin-input-item type="number" :disabled="$wire.lockPin">
                    <input data-hs-pin-input-item type="number" :disabled="$wire.lockPin">
                    <input data-hs-pin-input-item type="number" :disabled="$wire.lockPin">
                </div>
            </div>

        </form>

        <div class="h-12 w-full py-6 text-center text-sm font-semibold">
            <div wire:loading>
                <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            <span class="text-orange-500" wire:loading.remove x-show="$wire.showErrorIndicator" x-transition.out.opacity.duration.500ms
                  x-effect="if($wire.showErrorIndicator) {setTimeout(() => $wire.showErrorIndicator=false, 1500);}" x-cloak>
                您的授权码不正确或已过期，请联系服务商
            </span>
        </div>
    </div>
</div>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('loginpin', () => ({
            lockPin: @entangle('lockPin'),

            init() {
                HSPinInput.autoInit();
                const el = HSPinInput.getInstance('#pin');
                el.on('completed', ({
                    currentValue
                }) => {
                    this.lockPin = true;
                    this.$dispatch('setpin', currentValue.join(''));
                });
            }
        }));
    });
</script>
