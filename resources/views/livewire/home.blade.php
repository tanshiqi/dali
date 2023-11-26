<div>
    <div class="fixed inset-x-0 bottom-0 lg:inset-y-0 lg:z-50 lg:flex lg:w-96 lg:flex-col">
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-900 bg-gray-800 px-4 py-4 lg:px-6 lg:py-0">
            <div class="hidden h-16 shrink-0 items-center text-gray-100 lg:flex">
                <svg class="h-8" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
                    <path id="b"
                          d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
                </svg>
            </div>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-5 gap-x-3 gap-y-4 lg:gap-y-8">
                    <div class="col-span-2 lg:col-span-5">
                        <label class="mb-2 hidden text-sm font-medium leading-6 text-white lg:block" for="size">图片尺寸</label>
                        <div>
                            <select class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-sm text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:leading-6 [&_*]:text-black"
                                    id="size" name="size" wire:model.live='size' wire:change='sizeChanged'>
                                <option>512 x 512</option>
                                <option>640 x 360</option>
                                <option>360 x 640</option>
                                <option selected>1024 x 1024</option>
                                <option>1280 x 720</option>
                                <option>720 x 1280</option>
                                <option>2048 x 2048</option>
                                <option>2560 x 1440</option>
                                <option>1440 x 2560</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-span-3 lg:col-span-5">
                        <label class="mb-2 hidden text-sm font-medium leading-6 text-white lg:block" for="url">参考图 URL / 影响因子</label>
                        <div class="relative rounded-md shadow-sm">
                            <input class="block w-full rounded-md border-0 bg-white/5 py-1.5 pr-16 text-sm text-white ring-1 ring-inset ring-white/10 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:leading-6 [&_*]:text-black"
                                   id="url" name="url" type="text" wire:model.live.debounce='url' placeholder="参考图的完整 URL">
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <label class="sr-only" for="degree">参考图影响因子</label>
                                <select class="h-full rounded-md border-0 bg-transparent py-0 pl-2 pr-7 text-sm text-white focus:ring-2 focus:ring-inset focus:ring-sky-600 [&_*]:text-black"
                                        id="degree" name="degree" wire:model.number.live='change_degree'>
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
                        <p class="mt-2 hidden text-xs leading-5 text-gray-400 lg:block">数字 1-10，数值越大参考图影响越大。</p>
                    </div>

                    <div class="col-span-5">
                        <label class="mb-2 hidden text-sm font-medium leading-6 text-white lg:block" for="prompt">Prompt</label>
                        <div>
                            <textarea class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-sm text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-sky-500 sm:leading-6"
                                      id="prompt" name="prompt" rows="3" wire:model.live.debounce='prompt' placeholder="生图的文本描述，仅支持中文、日常标点符号"></textarea>
                        </div>
                        <p class="mt-2 hidden text-xs leading-5 text-gray-400 lg:block">生图的文本描述，仅支持中文、日常标点符号。不支持英文、特殊符号，限制 200 字。</p>
                    </div>
                    <div class="col-span-5 flex items-center justify-end">
                        <button class="w-full rounded-md bg-sky-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-sky-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-600 lg:w-auto"
                                type="submit">开始绘画</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-gray-800 px-4 py-4 shadow-sm sm:px-6 lg:hidden">
        <button class="-m-2.5 p-2.5 text-gray-200 lg:hidden" type="button">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <div class="flex flex-1 gap-3 text-sm font-semibold leading-6 text-gray-200">
            Dali AI Art
        </div>
        <a href="#">
            <span class="sr-only">Your profile</span>
            <img class="h-8 w-8 rounded-full bg-slate-50"
                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                 alt="">
        </a>
    </div>

    <main class="pb-64 pt-6 lg:pb-10 lg:pl-96 lg:pt-10">
        <div class="space-y-12 px-4 sm:px-6 lg:px-8">
            @foreach ($tasks as $task)
                <div class="flex" wire:key="task-{{ $task->id }}">
                    <div class="mr-4 flex-shrink-0">
                        <img class="w-10" src="http://ledoteaching.cdn.pinweb.io/20231125_s6ogPH.png">
                    </div>
                    <div class="flex-auto">
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-bold leading-none text-gray-50">Dali Bot </h4>
                            <span class="text-xs font-medium text-slate-400">{{ $task->created_at }}</span>
                        </div>

                        <p class="mt-1 max-w-4xl text-sm font-bold text-gray-100">
                            <a class="border-b border-transparent text-sky-500 hover:border-b-sky-500" href="{{ $task->url }}"
                               target="_blank">{{ $task->url }}</a>
                            {{ $task->prompt }}<span class="text-gray-300"> - @user</span>
                        </p>

                        @if ($task->result)
                            <a class="mt-2 inline-block overflow-hidden rounded-md bg-gray-800/70" href="{{ $task->result }}" target="_blank">
                                <img class="h-64 w-64 object-contain lg:h-80 lg:w-80" src="{{ $task->result }}">
                            </a>
                        @else
                            <div class="mt-2 flex h-64 w-64 items-center justify-center rounded-md bg-gray-800 lg:h-80 lg:w-80"
                                 wire:poll.5s='getResult("{{ $task->task_id }}")'>
                                <img class="h-12 w-12" src="/img/spin.gif">
                            </div>
                        @endif

                    </div>
                </div>
            @endforeach
        </div>

    </main>
</div>
