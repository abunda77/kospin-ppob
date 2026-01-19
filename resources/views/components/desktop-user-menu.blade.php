@props(['name'])

<flux:dropdown position="top" align="start" class="w-full">
    <flux:profile
        :name="$name"
        :initials="auth()->user()->initials()"
        icon-trailing="chevron-up"
        class="w-full cursor-pointer hover:bg-zinc-100 dark:hover:bg-zinc-800 rounded-lg p-2 transition-colors"
    />

    <flux:menu>
        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
            {{ __('Settings') }}
        </flux:menu.item>

        <flux:menu.separator />

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                {{ __('Log Out') }}
            </flux:menu.item>
        </form>
    </flux:menu>
</flux:dropdown>
