<div>
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-96 lg:flex-col">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-900 bg-gray-800 px-6">
            <div class="flex h-16 shrink-0 items-center text-gray-100">
                <svg class="h-8" aria-hidden="true" viewBox="0 0 32 32" stroke="currentColor" stroke-width="1.5" fill="none">
                    <path id="b"
                          d="M3.25 26v.75H7c1.305 0 2.384-.21 3.346-.627.96-.415 1.763-1.02 2.536-1.752.695-.657 1.39-1.443 2.152-2.306l.233-.263c.864-.975 1.843-2.068 3.071-3.266 1.209-1.18 2.881-1.786 4.621-1.786h5.791V5.25H25c-1.305 0-2.384.21-3.346.627-.96.415-1.763 1.02-2.536 1.751-.695.658-1.39 1.444-2.152 2.307l-.233.263c-.864.975-1.843 2.068-3.071 3.266-1.209 1.18-2.881 1.786-4.621 1.786H3.25V26Z" />
                </svg>
            </div>
            <form wire:submit.prevent="save">
                <div class="space-y-8">
                    <div class="">
                        <label class="block text-sm font-medium leading-6 text-white" for="country">图片尺寸</label>
                        <div class="mt-2">
                            <select class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6 [&_*]:text-black"
                                    id="size" name="size">
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
                    <div class="">
                        <label class="block text-sm font-medium leading-6 text-white" for="email">参考图 URL</label>
                        <div class="mt-2">
                            <input class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   id="url" name="url" type="url" wire:model.live.debounce='url'>
                        </div>
                    </div>
                    <div class="">
                        <label class="block text-sm font-medium leading-6 text-white" for="email">参考图影响因子</label>
                        <div class="mt-2">
                            <input class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                   id="change_degree" name="change_degree" type="text" wire:model.number.live='change_degree'
                                   placeholder="支持 1-10 内，数值越大参考图影响越大">
                        </div>
                    </div>
                    <div class="">
                        <label class="block text-sm font-medium leading-6 text-white" for="about">Prompt</label>
                        <div class="mt-2">
                            <textarea class="block w-full rounded-md border-0 bg-white/5 py-1.5 text-white shadow-sm ring-1 ring-inset ring-white/10 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-sm sm:leading-6"
                                      id="prompt" name="prompt" rows="4" wire:model.live.debounce='prompt'></textarea>
                        </div>
                        <p class="mt-2 text-xs leading-5 text-gray-400">生图的文本描述，仅支持中文、日常标点符号。不支持英文、特殊符号，限制 200 字。</p>
                    </div>
                    <div class="flex items-center justify-end">
                        <button class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                                type="submit">开始绘画</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-white px-4 py-4 shadow-sm sm:px-6 lg:hidden">
        <button class="-m-2.5 p-2.5 text-slate-700 lg:hidden" type="button">
            <span class="sr-only">Open sidebar</span>
            <svg class="h-6 w-6" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
            </svg>
        </button>
        <div class="flex-1 text-sm font-semibold leading-6 text-slate-900">Dashboard</div>
        <a href="#">
            <span class="sr-only">Your profile</span>
            <img class="h-8 w-8 rounded-full bg-slate-50"
                 src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80"
                 alt="">
        </a>
    </div>

    <main class="py-10 lg:pl-96">
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

                        <p class="mt-1 text-gray-100">{{ $task->prompt }}<span class="text-sm text-gray-300"> - @atan</span></p>



                        @if ($task->result)
                            <img class="mt-2 aspect-square h-80 rounded-md" src="{{ $task->result }}">
                        @else
                            <div class="mt-2 flex aspect-square h-80 items-center justify-center rounded-md bg-gray-600"
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
