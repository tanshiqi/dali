<div class="min-h-dvh bg-gray-700" x-data="{
    prompt: @entangle('prompt'),
    aiprovider: @entangle('aiprovider'),
    slideover: false,
    activeTab: 'tab1',
    init() {
        $watch('aiprovider', () => {
            this.activeTab = 'tab1'
        });
        $watch('slideover', () => {
            if (this.slideover) {
                document.body.classList.add('overflow-y-hidden');
            } else {
                document.body.classList.remove('overflow-y-hidden');
            }
        });
    }
}">
    {{-- 遮罩层 --}}
    <div class="pointer-events-none fixed inset-0 z-40 lg:hidden">
        <div class="absolute inset-0 bg-gray-700 bg-opacity-75 backdrop-blur-sm" x-show="slideover" x-transition.opacity.duration.500ms>
        </div>
    </div>

    {{-- panel --}}
    <div class="max-w-96 pointer-events-none fixed inset-y-0 z-40 flex w-full transform flex-col pr-10 transition duration-500 ease-in-out sm:w-96 sm:pr-0 lg:translate-x-0"
         :class="slideover ? '-translate-x-0' : '-translate-x-full'" x-cloak @click.outside="slideover=false">
        <div class="pointer-events-auto relative z-50 flex grow flex-col overflow-y-auto bg-gray-800 py-0">
            <div class="hidden h-16 shrink-0 items-center justify-between bg-gray-800 px-4 text-gray-100 lg:flex lg:px-6">
                <svg class="h-8" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
                    <path id="b"
                          d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
                </svg>
                <livewire:components.ai-selector wire:model='aiprovider' />
            </div>
            {{-- tabs begin --}}
            <div
                 class="relative flex h-[52px] flex-shrink-0 flex-col justify-end border-b border-gray-600 bg-gradient-to-b from-gray-800 to-gray-700/50 px-4 lg:px-6">
                <nav
                     class="*:px-4 *:border *:pb-2 *:pt-2.5 *:text-xs *:-mb-px *:font-semibold *:rounded-t-md is-active *:bg-gray-700 *:border-gray-600 *:text-gray-300 hover:*:bg-gray-600/70 hover:*:text-gray-300 flex space-x-2 [&>.is-active]:border-b-gray-800 [&>.is-active]:bg-gray-800 [&>.is-active]:text-sky-400">
                    <button type="button" :class="activeTab == 'tab1' ? 'is-active' : ''" @click.prevent ="activeTab='tab1'">通用参数</button>
                    <button type="button" :class="activeTab == 'tab2' ? 'is-active' : ''" @click.prevent ="activeTab='tab2'"
                            x-show="$wire.aiprovider=='Stable Diffusion'">SD 参数</button>
                </nav>
            </div>
            {{-- tabs end --}}

            {{-- tab1 --}}
            <form class="mt-6 flex-auto px-4 lg:px-6" wire:submit="save">
                <div class="space-y-6 lg:space-y-8" x-show="activeTab == 'tab1'">
                    <div>
                        <label class="mb-2 block text-sm font-medium leading-6 text-white" for="url">参考图 URL <span x-show="aiprovider=='Baidu AI'">/
                                影响因子</span>
                        </label>
                        <div class="flex w-full items-center">
                            <div class="relative flex-auto rounded-md shadow-sm">
                                <input class="block w-full rounded-md border-0 bg-gray-800 bg-white/5 py-1.5 text-sm leading-6 text-white ring-1 ring-inset ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 [&_*]:text-black"
                                       id="url" name="url" type="text" :class="aiprovider == 'Baidu AI' ? 'pr-16' : ''" wire:model='url'
                                       placeholder="参考图的完整 URL" autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center" x-show="aiprovider=='Baidu AI'">
                                    <label class="sr-only" for="degree">参考图影响因子</label>
                                    <select class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-sm text-white focus:ring-2 focus:ring-inset focus:ring-sky-600 [&_*]:text-black"
                                            id="degree" name="degree" wire:model.number='change_degree'>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                        <option>4</option>
                                        <option>5</option>
                                        <option>6</option>
                                        <option>7</option>
                                        <option>8</option>
                                        <option>9</option>
                                        <option>10</option>
                                    </select>
                                </div>
                            </div>
                            <label class="flex-shrink-0" for="photo">
                                <div
                                     class="ml-2 flex h-9 w-16 cursor-pointer items-center justify-center rounded bg-emerald-600 text-xs font-semibold text-white/80 shadow-sm hover:bg-emerald-700">
                                    <span wire:loading.remove wire:target="photo">上传图片</span>
                                    <img class="size-5" src="/img/loading.svg" wire:loading wire:target="photo">
                                </div>
                            </label>
                            <input class="hidden" id="photo" type="file" accept="image/*" wire:model='photo' />
                        </div>
                        <p class="mt-2 text-xs leading-5 text-gray-400">参考图可选。<span x-show="aiprovider=='Baidu AI'">影响因子数字 1-10，数值越大参考图影响越大。</span>
                        </p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium leading-6 text-white" for="size">图片尺寸 <span
                                  class="text-orange-500">*</span></label>
                        <div>
                            <select class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-sm leading-6 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-sky-500 [&_*]:text-black"
                                    id="size" name="size" wire:model='size' wire:change='sizeChanged'>
                                <option x-show="aiprovider != 'DALL-E'">512 x 512</option>
                                <option x-show="aiprovider != 'DALL-E'">640 x 360</option>
                                <option x-show="aiprovider != 'DALL-E'">360 x 640</option>
                                <option>1024 x 1024</option>
                                <option x-show="aiprovider != 'DALL-E'">1280 x 720</option>
                                <option x-show="aiprovider != 'DALL-E'">720 x 1280</option>
                                <option x-show="aiprovider != 'DALL-E'">2048 x 2048</option>
                                <option x-show="aiprovider != 'DALL-E'">2560 x 1440</option>
                                <option x-show="aiprovider != 'DALL-E'">1440 x 2560</option>
                                <option x-show="aiprovider != 'Baidu AI'">1024 x 1792</option>
                                <option x-show="aiprovider != 'Baidu AI'">1792 x 1024</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium leading-6 text-white" for="prompt">提示词 <span
                                  class="text-orange-500">*</span></label>
                        <div>
                            <textarea class="w-full resize-none rounded-md border-0 bg-white/5 py-1.5 align-top text-sm leading-6 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500"
                                      id="prompt" name="prompt" rows="4" wire:model='prompt' placeholder="生图的文本描述，仅支持中文、日常标点符号" autocomplete="off"></textarea>
                        </div>
                        <p class="mt-2 block text-xs leading-5 text-gray-400">生图的文本描述，Baidu AI 仅支持中文及日常标点符号，不支持英文、特殊符号，限制 200 字。</p>
                    </div>
                </div>


                {{-- tab2 --}}
                <div class="space-y-6 lg:space-y-8" x-show="activeTab == 'tab2'" x-cloak>
                    <div>
                        <label class="mb-2 block text-sm font-medium leading-6 text-white" for="negative_prompt">负面提示词（Negative Prompt）</label>
                        <div>
                            <textarea class="w-full resize-none rounded-md border-0 bg-white/5 py-1.5 align-top text-sm leading-6 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500"
                                      id="negative_prompt" name="negative_prompt" rows="3" wire:model='negative_prompt' placeholder="如果看到不想要的内容，请将其置于负面提示词中" autocomplete="off"></textarea>
                        </div>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium leading-6 text-white" for="sampler_name">采样方法（Sampler）</label>
                        <select class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-sm leading-6 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-sky-500 [&_*]:text-black"
                                id="sampler_name" name="sampler_name" wire:model='sampler_name' autocomplete="off">
                            <option>DPM++ 2M Karras</option>
                            <option>DPM++ SDE Karras</option>
                            <option>DPM++ 2M SDE Exponential</option>
                            <option>DPM++ 2M SDE Karras</option>
                            <option>Euler a</option>
                            <option>Euler</option>
                            <option>LMS</option>
                            <option>Heun</option>
                            <option>DPM2</option>
                            <option>DPM2 a</option>
                            <option>DPM++ 2S a</option>
                            <option>DPM++ 2M</option>
                            <option>DPM++ SDE</option>
                            <option>DPM++ 2M SDE</option>
                            <option>DPM++ 2M SDE Heun</option>
                            <option>DPM++ 2M SDE Heun Karras</option>
                            <option>DPM++ 2M SDE Heun Exponential</option>
                            <option>DPM++ 3M SDE</option>
                            <option>DPM++ 3M SDE Karras</option>
                            <option>DPM++ 3M SDE Exponential</option>
                            <option>DPM fast</option>
                            <option>DPM adaptive</option>
                            <option>LMS Karras</option>
                            <option>DPM2 Karras</option>
                            <option>DPM2 a Karras</option>
                            <option>DPM++ 2S a Karras</option>
                            <option>Restart</option>
                            <option>DDIM</option>
                            <option>PLMS</option>
                            <option>UniPC</option>
                            <option>LCM</option>
                        </select>
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-medium leading-6 text-white" for="steps">采样迭代步数（Steps）</label>
                            <span class="text-sm font-semibold text-white" x-text="$wire.steps"></span>
                        </div>
                        <input class="w-full cursor-pointer appearance-none bg-transparent focus:outline-none [&::-moz-range-thumb]:h-2.5 [&::-moz-range-thumb]:w-2.5 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-4 [&::-moz-range-thumb]:border-sky-500 [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:transition-all [&::-moz-range-thumb]:duration-150 [&::-moz-range-thumb]:ease-in-out [&::-moz-range-track]:h-2 [&::-moz-range-track]:w-full [&::-moz-range-track]:rounded-full [&::-moz-range-track]:bg-gray-700 [&::-webkit-slider-runnable-track]:h-2 [&::-webkit-slider-runnable-track]:w-full [&::-webkit-slider-runnable-track]:rounded-full [&::-webkit-slider-runnable-track]:bg-gray-700 [&::-webkit-slider-thumb]:-mt-0.5 [&::-webkit-slider-thumb]:h-2.5 [&::-webkit-slider-thumb]:w-2.5 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:shadow-[0_0_0_4px_rgba(14,165,233,1)] [&::-webkit-slider-thumb]:transition-all [&::-webkit-slider-thumb]:duration-150 [&::-webkit-slider-thumb]:ease-in-out"
                               id="steps" type="range" wire:model='steps' min="1" max="150">
                    </div>
                    <div>
                        <div class="mb-2 flex items-center justify-between">
                            <label class="block text-sm font-medium leading-6 text-white" for="cfg_scale">提示词相关性（CFG Scale）</label>
                            <span class="text-sm font-semibold text-white" x-text="$wire.cfg_scale"></span>
                        </div>
                        <input class="w-full cursor-pointer appearance-none bg-transparent focus:outline-none [&::-moz-range-thumb]:h-2.5 [&::-moz-range-thumb]:w-2.5 [&::-moz-range-thumb]:appearance-none [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-4 [&::-moz-range-thumb]:border-sky-500 [&::-moz-range-thumb]:bg-white [&::-moz-range-thumb]:transition-all [&::-moz-range-thumb]:duration-150 [&::-moz-range-thumb]:ease-in-out [&::-moz-range-track]:h-2 [&::-moz-range-track]:w-full [&::-moz-range-track]:rounded-full [&::-moz-range-track]:bg-gray-700 [&::-webkit-slider-runnable-track]:h-2 [&::-webkit-slider-runnable-track]:w-full [&::-webkit-slider-runnable-track]:rounded-full [&::-webkit-slider-runnable-track]:bg-gray-700 [&::-webkit-slider-thumb]:-mt-0.5 [&::-webkit-slider-thumb]:h-2.5 [&::-webkit-slider-thumb]:w-2.5 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-white [&::-webkit-slider-thumb]:shadow-[0_0_0_4px_rgba(14,165,233,1)] [&::-webkit-slider-thumb]:transition-all [&::-webkit-slider-thumb]:duration-150 [&::-webkit-slider-thumb]:ease-in-out"
                               id="cfg_scale" type="range" wire:model='cfg_scale' min="1" max="30" step="0.5">
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium leading-6 text-white" for="prompt_for_face">面部修复（Face Editor）</label>
                        <div>
                            <textarea class="w-full resize-none rounded-md border-0 bg-white/5 py-1.5 align-top text-sm leading-6 text-white shadow-sm ring-1 ring-inset ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500"
                                      id="prompt_for_face" name="prompt_for_face" rows="3" wire:model='prompt_for_face' placeholder="面部修复提示词" autocomplete="off"></textarea>
                        </div>
                    </div>

                </div>


                <div class="mt-8 flex items-center justify-end" x-cloak>
                    <button class="flex w-auto items-center rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 disabled:cursor-not-allowed disabled:bg-gray-700 disabled:text-gray-400"
                            type="submit" :disabled="prompt == ''" wire:loading.attr="disabled" wire:target="save">开始绘画

                        <img class="size-4 ml-1" src="/img/loading.svg" wire:loading wire:target="save">
                    </button>
                </div>


            </form>
            <div class="mb-4 mt-8 hidden items-center justify-between px-4 lg:flex lg:px-6">
                <button class="flex items-center rounded-md bg-white/5 px-3 py-2 text-sm font-semibold text-slate-400 hover:bg-white/10 hover:text-slate-300"
                        type="button" wire:click='quit' wire:confirm="您可以收藏当前的地址随时继续您的创作，确定现在要退出吗？">
                    <svg class="size-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M2 4.75A2.75 2.75 0 0 1 4.75 2h3a2.75 2.75 0 0 1 2.75 2.75v.5a.75.75 0 0 1-1.5 0v-.5c0-.69-.56-1.25-1.25-1.25h-3c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h3c.69 0 1.25-.56 1.25-1.25v-.5a.75.75 0 0 1 1.5 0v.5A2.75 2.75 0 0 1 7.75 14h-3A2.75 2.75 0 0 1 2 11.25v-6.5Zm9.47.47a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 1 1-1.06-1.06l.97-.97H5.25a.75.75 0 0 1 0-1.5h7.19l-.97-.97a.75.75 0 0 1 0-1.06Z"
                              clip-rule="evenodd" />
                    </svg>
                    退出
                </button>
                <div class="text-sm font-semibold text-gray-500" wire:ignore>
                    {{ auth()->user()?->client?->name }}
                </div>
            </div>
        </div>
    </div>

    <div class="sticky top-0 z-30 flex items-center gap-x-3 bg-gray-800/50 px-4 py-3 backdrop-blur sm:px-6 lg:hidden">
        <div class="flex flex-1 items-center text-gray-200 lg:hidden">
            <button @click.stop="slideover=true">
                <svg class="size-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M4 6l16 0" />
                    <path d="M4 12l16 0" />
                    <path d="M4 18l16 0" />
                </svg>
            </button>
        </div>

        <livewire:components.ai-selector wire:model='aiprovider' />

    </div>

    <main class="pt-6 lg:pl-96 lg:pt-10">
        <div class="space-y-12 px-4 sm:px-6 lg:px-8">
            @foreach ($tasks as $task)
                <div class="flex" wire:key="task-{{ $task->id }}">
                    <div class="mr-4 flex-shrink-0">
                        <img class="w-10" src="/img/bot.png">
                    </div>
                    <div class="flex-auto">
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold leading-none text-gray-50">Dali Bot </h4>
                            <span class="text-xs font-medium text-slate-400">{{ $task->created_at }}</span>
                        </div>

                        <p class="mt-1 max-w-4xl break-all text-sm font-bold text-gray-100">
                            @if ($task->url)
                                <a class="border-b border-transparent text-sky-500 hover:border-b-sky-500" href="{{ $task->url }}"
                                   target="_blank">{{ substr(md5($task->url), 0, 7) }}</a>
                            @endif
                            {{ $task->prompt }}<span class="font-normal text-gray-300"> - {{ '@user' }} ({{ $task->task_id }})</span>
                        </p>

                        @if ($task->result)
                            @if ($task->result == 'block.png')
                                <div class="mt-2 inline-block overflow-hidden rounded-md bg-gray-800/70">
                                    <img class="size-64 lg:size-80 object-contain"
                                         src="{{ Storage::disk('qiniu')->url($task->result) }}?imageView2/0/w/640/format/jpg">
                                </div>
                            @else
                                <div class="mt-2 inline-block cursor-pointer overflow-hidden rounded-md bg-gray-800/70"
                                     wire:click.stop="$dispatch('openModal', { component: 'viewer', arguments: { task: {{ $task->id }} }})">
                                    <img class="size-64 lg:size-80 object-contain"
                                         src="{{ Storage::disk('qiniu')->url($task->result) }}?imageView2/0/w/640/format/jpg">
                                </div>
                            @endif
                        @else
                            <div class="size-64 lg:size-80 mt-2 flex items-center justify-center rounded-md bg-gray-800" wire:poll>
                                <img class="size-12" src="/img/spin.gif">
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
        <div class="h-24" x-intersect.full="$wire.loadmore"></div>
    </main>
</div>
