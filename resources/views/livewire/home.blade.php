<div x-data="{ prompt: @entangle('prompt'), tools: false }" x-on:visible = "tools = true">
    <div class="fixed inset-x-0 bottom-0 transform transition lg:inset-y-0 lg:z-50 lg:flex lg:w-96 lg:translate-y-0 lg:flex-col" x-cloak
         :class="tools ? 'translate-y-0' : 'translate-y-14'">
        <div class="absolute inset-x-0 top-0 flex h-4 items-center justify-center lg:hidden" x-on:click="tools=!tools">
            <div class="h-1.5 w-12 rounded-full bg-white/10"></div>
        </div>
        <div
             class="flex grow flex-col gap-y-5 overflow-y-auto border border-gray-800 bg-[#2f3543] px-4 pb-3 pt-5 lg:justify-between lg:bg-gray-800 lg:px-6 lg:py-0">
            <div class="hidden h-16 shrink-0 items-center text-gray-100 lg:flex lg:justify-between">
                <svg class="h-8" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
                    <path id="b"
                          d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
                </svg>
                <div>
                    <div class="relative" x-data="{
                        open: false,
                        toggle() {
                            if (this.open) {
                                return this.close()
                            }
                            this.$refs.button.focus()
                            this.open = true
                        },
                        close(focusAfter) {
                            if (!this.open) return
                            this.open = false
                            focusAfter && focusAfter.focus()
                        }
                    }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                         x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']">
                        <div class="inline-flex divide-x divide-sky-700 rounded-md shadow-sm">
                            <div class="inline-flex items-center gap-x-1.5 rounded-l-md bg-sky-600 px-3 py-1.5 text-white shadow-sm">
                                <p class="text-xs font-semibold" x-text="$wire.aimodel"></p>
                            </div>
                            <button class="inline-flex items-center rounded-l-none rounded-r-md bg-sky-600 p-1.5 hover:bg-sky-700 focus:outline-none focus:ring-0 focus:ring-sky-600"
                                    type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label" x-ref="button"
                                    x-on:click="toggle()" :aria-expanded="open" :aria-controls="$id('dropdown-button')">
                                <span class="sr-only">Change published status</span>
                                <svg class="h-5 w-5 text-white" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                          clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <ul class="absolute right-0 z-10 mt-2 w-60 origin-top-right divide-y divide-gray-200 overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            x-transition.origin.top.right x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)">
                            <li class="group cursor-default select-none p-4 text-sm text-gray-900 hover:bg-sky-600 hover:text-white"
                                @click="$wire.aimodel='Stable Diffusion';close($refs.button)">
                                <div class="flex flex-col">
                                    <div class="flex justify-between">
                                        <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                                        <p :class="$wire.aimodel == 'Stable Diffusion' ? 'font-semibold' : 'font-normal'">Stable Diffusion</p>
                                        <span class="text-sky-600 group-hover:text-white">
                                            <svg class="h-4 w-4" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor"
                                                 x-show="$wire.aimodel=='Stable Diffusion'">
                                                <path fill-rule="evenodd"
                                                      d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 group-hover:text-sky-200">基于 Stable Diffusion 的图像生成模型</p>
                                </div>
                            </li>
                            <li class="group cursor-default select-none p-4 text-sm text-gray-900 hover:bg-sky-600 hover:text-white"
                                @click="$wire.aimodel='Baidu AI';close($refs.button)">
                                <div class="flex flex-col">
                                    <div class="flex justify-between">
                                        <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                                        <p :class="$wire.aimodel == 'Baidu AI' ? 'font-semibold' : 'font-normal'">Baidu AI</p>
                                        <span class="text-sky-600 group-hover:text-white">
                                            <svg class="h-4 w-4" aria-hidden="true" viewBox="0 0 20 20" fill="currentColor" x-show="$wire.aimodel=='Baidu AI'">
                                                <path fill-rule="evenodd"
                                                      d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                                      clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500 group-hover:text-sky-200">基于百度文心人工智能的图像生成模型</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <form class="flex-auto" wire:submit="save">
                <div class="grid grid-cols-5 gap-x-2 gap-y-3 lg:gap-x-3 lg:gap-y-8">
                    <div class="col-span-5 flex items-center gap-x-2 lg:hidden">
                        <label class="flex h-11 w-11 flex-shrink-0 cursor-pointer items-center justify-center rounded-full bg-gray-800 text-gray-300"
                               for="photo">
                            <svg class="h-5 w-5" wire:loading.remove wire:target="photo" xmlns="http://www.w3.org/2000/svg" width="44" height="44"
                                 viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                      d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
                                <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                            </svg>
                            <img class="h-5 w-5" src="/img/loading.svg" wire:loading wire:target="photo">
                        </label>
                        <input class="hidden" id="photo" type="file" accept="image/*" wire:model='photo' />



                        <input class="block w-full rounded-full border-0 bg-gray-800 px-4 py-2.5 text-white ring-1 ring-inset ring-gray-900/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 lg:hidden [&_*]:text-black"
                               name="prompt" type="text" wire:model='prompt' placeholder="提示词，仅支持中文及标点" autocomplete="off">

                        <button class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-full bg-sky-600 text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-sky-500 disabled:cursor-not-allowed disabled:bg-gray-800 disabled:text-gray-600 lg:w-auto"
                                type="submit" :disabled="prompt == ''" wire:loading.attr="disabled" wire:target="save">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" wire:loading.remove wire:target="save">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.581-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                            </svg>
                            <img class="h-5 w-5" src="/img/loading.svg" wire:loading wire:target="save">
                        </button>

                    </div>

                    <div class="col-span-3 lg:col-span-5">
                        <label class="mb-2 hidden text-sm font-medium leading-6 text-white lg:block" for="url">参考图 URL / 影响因子</label>
                        <div class="flex w-full items-center">
                            <div class="relative flex-auto rounded-md shadow-sm">
                                <input class="block w-full rounded-full border-0 bg-gray-800 py-2.5 pr-16 text-white ring-1 ring-inset ring-gray-900/50 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:leading-6 lg:rounded-md lg:bg-white/5 lg:py-1.5 lg:text-sm lg:ring-white/10 [&_*]:text-black"
                                       id="url" name="url" type="text" wire:model='url' placeholder="参考图的完整 URL" autocomplete="off">
                                <div class="absolute inset-y-0 right-0 flex items-center">
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
                            <label class="hidden flex-shrink-0 lg:block" for="photo">
                                <div
                                     class="ml-2 flex h-8 w-16 cursor-pointer items-center justify-center rounded bg-white/10 text-xs text-gray-300 shadow-sm hover:bg-white/5">
                                    <span wire:loading.remove wire:target="photo">上传图片</span>
                                    <img class="h-5 w-5" src="/img/loading.svg" wire:loading wire:target="photo">
                                </div>

                            </label>
                        </div>

                        <p class="mt-2 hidden text-xs leading-5 text-gray-400 lg:block">参考图可选；影响因子数字 1-10，数值越大参考图影响越大。</p>
                    </div>

                    <div class="col-span-2 lg:col-span-5">
                        <label class="mb-2 hidden text-sm font-medium leading-6 text-white lg:block" for="size">图片尺寸 <span
                                  class="text-orange-500">*</span></label>
                        <div>
                            <select class="block w-full rounded-full border-0 bg-gray-800 py-2.5 text-white shadow-sm ring-1 ring-inset ring-gray-900/50 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:leading-6 lg:rounded-md lg:bg-white/5 lg:py-1.5 lg:text-sm lg:ring-white/10 [&_*]:text-black"
                                    id="size" name="size" wire:model='size' wire:change='sizeChanged'>
                                <option selected>512 x 512</option>
                                <option>640 x 360</option>
                                <option>360 x 640</option>
                                <option>1024 x 1024</option>
                                <option>1280 x 720</option>
                                <option>720 x 1280</option>
                                <option>2048 x 2048</option>
                                <option>2560 x 1440</option>
                                <option>1440 x 2560</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-span-5 hidden lg:block">
                        <label class="mb-2 hidden text-sm font-medium leading-6 text-white lg:block" for="prompt">Prompt <span
                                  class="text-orange-500">*</span></label>
                        <div>
                            <textarea class="w-full resize-none rounded-md border-0 bg-white/5 py-1.5 text-sm text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:leading-6"
                                      id="prompt" name="prompt" rows="4" wire:model='prompt' placeholder="生图的文本描述，仅支持中文、日常标点符号" autocomplete="off"></textarea>
                        </div>
                        <p class="mt-2 hidden text-xs leading-5 text-gray-400 lg:block">生图的文本描述，仅支持中文及日常标点符号。不支持英文、特殊符号，限制 200 字。</p>
                    </div>




                    <div class="col-span-5 hidden items-center justify-end lg:flex">
                        <button class="flex w-full items-center rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 disabled:cursor-not-allowed disabled:bg-gray-700 disabled:text-gray-400 lg:w-auto"
                                type="submit" :disabled="prompt == ''" wire:loading.attr="disabled" wire:target="save">开始绘画

                            <img class="ml-1 h-4 w-4" src="/img/loading.svg" wire:loading wire:target="save">
                        </button>
                    </div>

                </div>
            </form>
            <div class="mb-4 hidden items-center justify-between lg:flex">
                <button class="flex items-center rounded-md bg-white/5 px-3 py-2 text-sm font-semibold text-slate-400 hover:bg-white/10 hover:text-slate-300"
                        type="button" wire:click='quit' wire:confirm="您可以收藏当前的地址随时继续您的创作，确定现在要退出吗？">
                    <svg class="mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
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

    <div class="sticky top-0 z-40 flex items-center gap-x-6 border-b border-gray-800 bg-[#2f3543] px-4 py-3 sm:px-6 lg:hidden">
        <div class="flex flex-1 items-center gap-3 text-sm font-semibold leading-6 text-gray-200">
            <svg class="h-7" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
                <path id="b"
                      d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
            </svg>Dali AI
        </div>
        <button class="flex items-center rounded-md text-sm text-white/60 lg:hidden" type="button" wire:click='quit'
                wire:confirm="您可以收藏当前的地址随时继续您的创作，确定现在要退出吗？">
            退出<svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor">
                <path fill-rule="evenodd"
                      d="M2 4.75A2.75 2.75 0 0 1 4.75 2h3a2.75 2.75 0 0 1 2.75 2.75v.5a.75.75 0 0 1-1.5 0v-.5c0-.69-.56-1.25-1.25-1.25h-3c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h3c.69 0 1.25-.56 1.25-1.25v-.5a.75.75 0 0 1 1.5 0v.5A2.75 2.75 0 0 1 7.75 14h-3A2.75 2.75 0 0 1 2 11.25v-6.5Zm9.47.47a.75.75 0 0 1 1.06 0l2.25 2.25a.75.75 0 0 1 0 1.06l-2.25 2.25a.75.75 0 1 1-1.06-1.06l.97-.97H5.25a.75.75 0 0 1 0-1.5h7.19l-.97-.97a.75.75 0 0 1 0-1.06Z"
                      clip-rule="evenodd" />
            </svg>

        </button>

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
                            <a class="mt-2 inline-block overflow-hidden rounded-md bg-gray-800/70" href="{{ Storage::disk('qiniu')->url($task->result) }}"
                               target="_blank">
                                <img class="h-64 w-64 object-contain lg:h-80 lg:w-80"
                                     src="{{ Storage::disk('qiniu')->url($task->result) }}?imageView2/0/w/640/format/jpg">
                            </a>
                        @else
                            <div class="mt-2 flex h-64 w-64 items-center justify-center rounded-md bg-gray-800 lg:h-80 lg:w-80" wire:poll>
                                <img class="h-12 w-12" src="/img/spin.gif">
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>
        <div class="h-48" x-intersect.full="$wire.loadmore"></div>
    </main>
</div>
