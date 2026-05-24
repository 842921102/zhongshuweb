<x-filament-widgets::widget>
    <x-filament::section heading="快捷管理">
        <p class="admin-welcome">欢迎回来，{{ $this->getUserName() }}</p>
        <p class="admin-welcome-sub">从下方入口快速进入常用管理模块</p>

        <div class="admin-quick-grid" style="margin-top: 1.25rem">
            @foreach ($this->getLinks() as $link)
                <a href="{{ $link['url'] }}" class="admin-quick-card">
                    @include('filament.widgets.partials.quick-icon', ['name' => $link['icon']])
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
