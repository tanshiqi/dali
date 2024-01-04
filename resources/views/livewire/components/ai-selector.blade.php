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
     x-on:focusin.window="! $refs.panel.contains($event.target) && close()" x-id="['dropdown-button']" x-cloak>
    {{-- <div class="inline-flex divide-x divide-sky-700 rounded-md shadow-sm"> --}}
    {{-- <div class="inline-flex items-center gap-x-1.5 rounded-l-md bg-sky-600 px-3 py-1.5 text-white shadow-sm">
            <p class="text-xs font-semibold" x-text="$wire.value"></p>
        </div> --}}
    <button class="inline-flex items-center gap-x-0.5 rounded-md bg-sky-600 p-1.5 pl-3 text-white hover:bg-sky-700 focus:outline-none focus:ring-0 focus:ring-sky-600"
            type="button" x-ref="button" x-on:click="toggle()">
        <p class="text-xs font-semibold" x-text="$wire.value"></p>
        <svg class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                  d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                  clip-rule="evenodd" />
        </svg>
    </button>
    {{-- </div> --}}
    <ul class="absolute right-0 z-10 mt-2 w-[250px] origin-top-right divide-y divide-gray-200 overflow-hidden rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
        x-transition.origin.top.right x-ref="panel" x-show="open" x-on:click.outside="close($refs.button)">
        <li class="group cursor-default select-none p-4 text-sm text-gray-900 hover:bg-sky-600 hover:text-white"
            @click="$wire.value='Stable Diffusion';close($refs.button)">
            <div class="flex flex-col">
                <div class="flex justify-between">
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <p :class="$wire.value == 'Stable Diffusion' ? 'font-semibold' : 'font-normal'">Stable Diffusion</p>
                    <span class="text-sky-600 group-hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" x-show="$wire.value=='Stable Diffusion'">
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
            @click="$wire.value='Baidu AI';close($refs.button)">
            <div class="flex flex-col">
                <div class="flex justify-between">
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <p :class="$wire.value == 'Baidu AI' ? 'font-semibold' : 'font-normal'">Baidu AI</p>
                    <span class="text-sky-600 group-hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" x-show="$wire.value=='Baidu AI'">
                            <path fill-rule="evenodd"
                                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <p class="mt-1 text-xs text-gray-500 group-hover:text-sky-200">基于百度文心人工智能的图像生成模型</p>
            </div>
        </li>
        <li class="group cursor-default select-none p-4 text-sm text-gray-900 hover:bg-sky-600 hover:text-white"
            @click="$wire.value='DALL-E';close($refs.button)">
            <div class="flex flex-col">
                <div class="flex justify-between">
                    <!-- Selected: "font-semibold", Not Selected: "font-normal" -->
                    <p :class="$wire.value == 'DALL-E' ? 'font-semibold' : 'font-normal'">DALL-E</p>
                    <span class="text-sky-600 group-hover:text-white">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" x-show="$wire.value=='DALL-E'">
                            <path fill-rule="evenodd"
                                  d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z"
                                  clip-rule="evenodd" />
                        </svg>
                    </span>
                </div>
                <p class="mt-1 text-xs text-gray-500 group-hover:text-sky-200">基于 OpenAI DALL-E 3 图像生成模型</p>
            </div>
        </li>
    </ul>
</div>
