<div 
    x-data="{}"
    class="flex items-center w-full transition-all duration-300 ease-in-out"
    :class="($store.sidebar && !$store.sidebar.isOpen && $el.closest('.fi-sidebar')) ? 'justify-center' : 'gap-3 px-1'"
>
    <!-- Logo BKK -->
    <img 
        src="{{ asset('images/bkksorong1.gif') }}" 
        alt="Logo BKK" 
        class="h-8 w-auto object-contain flex-shrink-0 drop-shadow-sm"
    >

    <span 
        x-show="!$el.closest('.fi-sidebar') || ($store.sidebar ? $store.sidebar.isOpen : true)" 
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-x-1"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-cloak
        class="text-lg font-black tracking-wider text-teal-400 whitespace-nowrap ml-1"
    >
        SI-VAKSIN
    </span>
</div>