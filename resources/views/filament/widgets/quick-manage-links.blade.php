<x-filament-widgets::widget>
    <x-filament::section heading="快捷管理">
        <div class="admin-quick-grid">
            @foreach ($this->getLinks() as $link)
                <a href="{{ $link['url'] }}" class="admin-quick-card">
                    @include('filament.widgets.partials.quick-icon', ['name' => $link['icon']])
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
