<div class="absolute inset-0 flex h-full w-full flex-col bg-gray-950 lg:flex-row">
    <div class="flex h-full w-full cursor-pointer items-center justify-center overflow-hidden p-1 lg:p-4" wire:click="$dispatch('closeModal')">
        <img class="max-h-full max-w-full rounded-md object-contain" src="{{ Storage::disk('qiniu')->url($task['result']) . '?imageView2/0/w/1000/format/jpg' }}">
    </div>
    <div class="flex max-h-full flex-shrink-0 flex-col justify-between bg-gray-900/50 p-4 text-gray-300 lg:w-96 lg:p-7">
        <div class="flex flex-1 flex-col gap-y-4">
            <div class="hidden items-center justify-end lg:flex">
                <button class="rounded-full p-2 hover:bg-gray-800 focus-visible:outline-none" type="button" wire:click="$dispatch('closeModal')">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm font-medium lg:text-base">{{ $task['prompt'] }}</p>
            <div
                 class="*:inline-flex *:items-center *:rounded-md *:bg-gray-400/10 *:px-2 *:py-1 *:text-xs *:font-medium *:text-gray-400 *:ring-1 *:ring-inset *:ring-gray-400/20 *:whitespace-nowrap *:mr-0.5 *:mb-0.5">
                <span>{{ $task['aiprovider'] }}</span>
                @if ($task['aiprovider'] == 'Stable Diffusion')
                    <span>{{ 'Sampler ' . data_get($task, 'params.sampler_name') }}</span>
                    <span>{{ 'Steps ' . data_get($task, 'params.steps') }}</span>
                    <span>{{ 'CFG Scale ' . data_get($task, 'params.cfg_scale') }}</span>
                @endif

                <span>{{ $task['width'] . ' x ' . $task['height'] }}</span>

            </div>

            @if ($task['aiprovider'] == 'Midjourney' && !data_get($task, 'params.main_task'))
                <div class="mt-4 flex items-start justify-between gap-x-4">
                    <div>
                        <h2 class="flex items-center gap-x-1 text-sm font-semibold leading-4 text-sky-500">
                            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5 4a.75.75 0 0 1 .738.616l.252 1.388A1.25 1.25 0 0 0 6.996 7.01l1.388.252a.75.75 0 0 1 0 1.476l-1.388.252A1.25 1.25 0 0 0 5.99 9.996l-.252 1.388a.75.75 0 0 1-1.476 0L4.01 9.996A1.25 1.25 0 0 0 3.004 8.99l-1.388-.252a.75.75 0 0 1 0-1.476l1.388-.252A1.25 1.25 0 0 0 4.01 6.004l.252-1.388A.75.75 0 0 1 5 4ZM12 1a.75.75 0 0 1 .721.544l.195.682c.118.415.443.74.858.858l.682.195a.75.75 0 0 1 0 1.442l-.682.195a1.25 1.25 0 0 0-.858.858l-.195.682a.75.75 0 0 1-1.442 0l-.195-.682a1.25 1.25 0 0 0-.858-.858l-.682-.195a.75.75 0 0 1 0-1.442l.682-.195a1.25 1.25 0 0 0 .858-.858l.195-.682A.75.75 0 0 1 12 1ZM10 11a.75.75 0 0 1 .728.568.968.968 0 0 0 .704.704.75.75 0 0 1 0 1.456.968.968 0 0 0-.704.704.75.75 0 0 1-1.456 0 .968.968 0 0 0-.704-.704.75.75 0 0 1 0-1.456.968.968 0 0 0 .704-.704A.75.75 0 0 1 10 11Z"
                                      clip-rule="evenodd" />
                            </svg>
                            细化图片
                        </h2>
                        <p class="mt-2 text-xs font-medium leading-normal text-gray-400">选择您需要放大的图像，生成更大的尺寸，并添加更多细节。</p>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center justify-center" wire:loading.flex wire:target="refining">
                            <svg class="h-5 w-5 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                        </div>
                        <div class="*:items-center *:justify-center *:text-xs *:font-semibold *:text-white *:border-b *:border-r *:aspect-[3/2] hover:*:bg-sky-600 *:border-gray-600 *:bg-gray-800/50 focus-visible:*:outline-none grid w-[88px] flex-shrink-0 grid-cols-2 items-center overflow-hidden rounded-md border-l border-t border-gray-600"
                             wire:loading.class='opacity-30 pointer-events-none' wire:target="refining">
                            <button type="button" @click.stop="$wire.refining(1)">U1</button>
                            <button class="rounded-tr-md" type="button" @click.stop="$wire.refining(2)">U2</button>
                            <button class="rounded-bl-md" type="button" @click.stop="$wire.refining(3)">U3</button>
                            <button class="rounded-br-md" type="button" @click.stop="$wire.refining(4)">U4</button>
                        </div>
                    </div>

                </div>
            @endif

        </div>


        <div class="mt-6 flex flex-shrink-0 items-center justify-between gap-x-2 lg:gap-x-4">
            <button class="rounded-md bg-white/10 px-3.5 py-2 text-xs font-semibold text-gray-300 shadow-sm hover:bg-white/20 focus:border-0 focus:ring-0 focus-visible:outline-none lg:w-1/2 lg:py-2.5 lg:text-sm"
                    type="button" x-data="{ btntext: '复制提示词' }" @click="$clipboard('{{ $task['prompt'] }}');btntext='　已复制　'" x-text="btntext"
                    x-effect="if(btntext != '复制提示词') setTimeout(() => btntext='复制提示词',1500)"></button>
            <a class="block rounded-md bg-white/10 px-3.5 py-2 text-center text-xs font-semibold text-gray-300 shadow-sm hover:bg-white/20 focus:border-0 focus:ring-0 focus-visible:outline-none lg:w-1/2 lg:py-2.5 lg:text-sm"
               type="button" href="{{ Storage::disk('qiniu')->url($task['result']) . '?attname=' . Str::random(12) . '.png' }}">下载原图</a>
            <div class="flex-auto text-right lg:hidden">
                <button class="rounded-full p-2 hover:bg-gray-800 focus-visible:outline-none" type="button" wire:click="$dispatch('closeModal')">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
