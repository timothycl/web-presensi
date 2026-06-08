<style>
    .fi-logo-wrapper .logo-laptop { opacity: 0; transform: scale(0.5) rotate(45deg); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .fi-logo-wrapper .logo-bolt { opacity: 1; transform: scale(1) rotate(0); transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .fi-logo-wrapper:hover .logo-bolt { opacity: 0; transform: scale(0.5) rotate(-45deg); }
    .fi-logo-wrapper:hover .logo-laptop { opacity: 1; transform: scale(1) rotate(0); }
</style>

<div 
    x-data="{}" 
    class="fi-logo-wrapper flex items-center gap-3 group py-1 select-none"
    title="Timothy's Company"
>
    <div class="relative flex items-center justify-center">
        <!-- Glow Effect Behind Icon -->
        <div class="absolute inset-x-[-4px] inset-y-[-4px] bg-amber-500/0 blur-xl rounded-full group-hover:bg-amber-500/40 transition-all duration-700 ease-out"></div>
        
        <!-- Icon Container -->
        <div class="fi-logo-icon relative bg-gradient-to-br from-amber-400 to-amber-600 rounded-xl shadow-lg transform transition-all duration-500 cubic-bezier(0.34, 1.56, 0.64, 1) group-hover:scale-110 group-hover:rotate-[12deg] group-hover:shadow-amber-500/50 group-active:scale-95 group-active:rotate-0" style="width: 2.25rem; height: 2.25rem; display: grid; place-items: center;">
            <!-- Bolt Icon (Default) -->
            <svg class="logo-bolt text-white" style="width: 1.25rem; height: 1.25rem; grid-area: 1/1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>

            <!-- Computer Icon (On Hover) -->
            <svg class="logo-laptop text-white" style="width: 1.25rem; height: 1.25rem; grid-area: 1/1;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
        </div>
    </div>
    
    <div class="flex items-center whitespace-nowrap overflow-hidden">
        <span class="font-black text-lg leading-tight tracking-tight text-gray-900 dark:text-white transition-all duration-500 group-hover:text-amber-500 group-hover:translate-x-0.5">
            Timothy's
        </span>
        <span class="ml-1.5 text-[10px] uppercase font-bold tracking-[0.2em] text-amber-600 dark:text-amber-400 opacity-80 group-hover:opacity-100 group-hover:translate-x-1 transition-all duration-500">
            Company
        </span>
    </div>
</div>
