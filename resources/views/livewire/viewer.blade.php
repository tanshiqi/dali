<div class="absolute inset-0 flex h-full w-full flex-col lg:flex-row">
    <div class="flex h-full w-full cursor-pointer items-center justify-center overflow-hidden p-1 lg:p-4" wire:click="$dispatch('closeModal')">
        <img class="max-h-full max-w-full rounded-md object-contain" src="{{ Storage::disk('qiniu')->url($task->result) . '?imageView2/0/w/1000/format/jpg' }}">
    </div>
    <div class="lg:max-w-96 flex max-h-full flex-shrink-0 flex-col justify-between bg-gray-900/50 p-4 text-gray-300 lg:p-7">
        <div class="flex flex-1 flex-col gap-y-4">
            <div class="hidden items-center justify-end lg:flex">
                <button class="rounded-full p-2 hover:bg-gray-800 focus-visible:outline-none" type="button" wire:click="$dispatch('closeModal')">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p class="text-sm font-medium lg:text-base">{{ $task->prompt }}</p>
            <div
                 class="*:inline-flex *:items-center *:rounded-md *:bg-gray-400/10 *:px-2 *:py-1 *:text-xs *:font-medium *:text-gray-400 *:ring-1 *:ring-inset *:ring-gray-400/20 *:whitespace-nowrap *:mr-0.5 *:mb-0.5">
                <span>{{ 'Sampler ' . data_get($task->sdparams, 'sampler_name') }}</span>
                <span>{{ 'Steps ' . data_get($task->sdparams, 'steps') }}</span>
                <span>{{ 'CFG Scale ' . data_get($task->sdparams, 'cfg_scale') }}</span>
            </div>
        </div>


        <div class="mt-6 flex flex-shrink-0 items-center justify-between gap-x-2 lg:gap-x-4">
            <button class="rounded-md bg-white/10 px-3.5 py-2 text-xs font-semibold text-gray-300 shadow-sm hover:bg-white/20 focus:border-0 focus:ring-0 focus-visible:outline-none lg:w-1/2 lg:py-2.5 lg:text-sm"
                    type="button" x-data="{ btntext: '复制提示词' }" @click="$clipboard('{{ $task->prompt }}');btntext='　已复制　'" x-text="btntext"
                    x-effect="if(btntext != '复制提示词') setTimeout(() => btntext='复制提示词',1500)"></button>
            <a class="block rounded-md bg-white/10 px-3.5 py-2 text-center text-xs font-semibold text-gray-300 shadow-sm hover:bg-white/20 focus:border-0 focus:ring-0 focus-visible:outline-none lg:w-1/2 lg:py-2.5 lg:text-sm"
               type="button" href="{{ Storage::disk('qiniu')->url($task->result) . '?attname=' . Str::random(12) . '.png' }}">下载原图</a>
            <div class="flex-auto text-right">
                <button class="rounded-full p-2 hover:bg-gray-800 focus-visible:outline-none" type="button" wire:click="$dispatch('closeModal')">
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>
