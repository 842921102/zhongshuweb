<div class="monkey-login-page">
    @include('filament.partials.monkey-avatar')

    <div class="login-card" id="loginCard">
        <div class="login-header">
            <h1 class="login-title">众鼠CMS管理系统</h1>
            <p class="login-subtitle">欢迎回来，请登录你的账号</p>
        </div>

        <div class="monkey-login-form">
            {{ $this->content }}
        </div>
    </div>

    <x-filament-actions::modals />
</div>
