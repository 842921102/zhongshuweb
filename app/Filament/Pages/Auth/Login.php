<?php

namespace App\Filament\Pages\Auth;

use Filament\Actions\Action;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class Login extends BaseLogin
{
    protected string $view = 'filament.pages.auth.login';

    protected static string $layout = 'filament.layouts.monkey-login';

    protected static ?string $title = '登录';

    /**
     * @var array<string, string>
     */
    protected array $extraBodyAttributes = [
        'class' => 'monkey-login-body',
    ];

    public function hasLogo(): bool
    {
        return false;
    }

    public function getHeading(): string|Htmlable|null
    {
        return null;
    }

    public function getSubheading(): string|Htmlable|null
    {
        return null;
    }

    public function hasTopbar(): bool
    {
        return false;
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('账号')
            ->id('username')
            ->placeholder('请输入账号或邮箱')
            ->required()
            ->autocomplete('username')
            ->autofocus();
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('密码')
            ->id('password')
            ->placeholder('请输入密码')
            ->hint(new HtmlString('<a href="#" class="monkey-forgot-link" onclick="event.preventDefault(); alert(\'请联系管理员重置密码\')">忘记密码</a>'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->autocomplete('current-password')
            ->required();
    }

    protected function getRememberFormComponent(): Component
    {
        return parent::getRememberFormComponent()
            ->label('记住我');
    }

    protected function getAuthenticateFormAction(): Action
    {
        return parent::getAuthenticateFormAction()
            ->label('登 录');
    }
}
