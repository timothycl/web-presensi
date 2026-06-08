<div
    x-data="{}"
    class="fi-ta-header-action"
>
    <x-filament::button
        color="gray"
        icon="heroicon-m-adjustments-horizontal"
        size="sm"
        x-on:click.stop.prevent="
            const ctn = $el.closest('.fi-ta-ctn') || document.querySelector('.fi-ta-ctn');
            if (ctn) {
                ctn.classList.toggle('selection-mode-active');
            }
        "
    >
        Manage
    </x-filament::button>
</div>
