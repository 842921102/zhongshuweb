{{-- 猴子登录页布局 --}}
<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
        @include('filament.partials.monkey-login-styles')
    @endpush

    <div class="login-wrapper">
        {{ $slot }}
    </div>

    @push('scripts')
        @include('filament.partials.monkey-login-scripts')
    @endpush
</x-filament-panels::layout.base>
