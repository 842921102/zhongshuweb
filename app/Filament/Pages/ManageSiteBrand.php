<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use App\Support\AdminPermissionRegistry;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Throwable;

/**
 * @property-read Schema $form
 */
class ManageSiteBrand extends Page
{
    use CanUseDatabaseTransactions;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = '品牌 Logo';

    protected static ?string $title = '品牌 Logo';

    protected static string|\UnitEnum|null $navigationGroup = '系统设置';

    protected static ?int $navigationSort = 0;

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->form->fill([
            'header_logo_default' => SiteSetting::get('header_logo_default'),
            'header_logo_scrolled' => SiteSetting::get('header_logo_scrolled'),
            'footer_logo' => SiteSetting::get('footer_logo'),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        $disk = upload_disk();

        return $schema
            ->components([
                Section::make('顶部导航')
                    ->description('首页 Banner 等深色背景使用透明底 Logo；页面向下滚动后白底导航使用深色 Logo。')
                    ->schema([
                        FileUpload::make('header_logo_default')
                            ->label('透明底 Logo')
                            ->image()
                            ->directory('settings/logos')
                            ->disk($disk)
                            ->helperText('用于深色 / 透明导航栏，建议使用浅色或白字 Logo'),
                        FileUpload::make('header_logo_scrolled')
                            ->label('滚动后 Logo')
                            ->image()
                            ->directory('settings/logos')
                            ->disk($disk)
                            ->helperText('用于白底导航栏，建议使用深色 Logo'),
                    ])
                    ->columns(2),
                Section::make('页脚')
                    ->schema([
                        FileUpload::make('footer_logo')
                            ->label('页脚 Logo')
                            ->image()
                            ->directory('settings/logos')
                            ->disk($disk),
                    ]),
            ]);
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();

            $definitions = [
                'header_logo_default' => ['group' => 'header', 'label' => '顶部Logo(透明底)'],
                'header_logo_scrolled' => ['group' => 'header', 'label' => '顶部Logo(滚动后)'],
                'footer_logo' => ['group' => 'footer', 'label' => '页脚Logo'],
            ];

            foreach ($definitions as $key => $meta) {
                SiteSetting::query()->updateOrCreate(
                    ['key' => $key],
                    [
                        'value' => $data[$key] ?? null,
                        'group' => $meta['group'],
                        'label' => $meta['label'],
                        'type' => 'image',
                    ],
                );
            }

            $this->commitDatabaseTransaction();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }

        Notification::make()
            ->title('Logo 已保存')
            ->success()
            ->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFormContentComponent(),
            ]);
    }

    public function getFormContentComponent(): Component
    {
        return Form::make([EmbeddedSchema::make('form')])
            ->id('form')
            ->livewireSubmitHandler('save')
            ->footer([
                Actions::make($this->getFormActions())
                    ->alignment($this->getFormActionsAlignment())
                    ->fullWidth($this->hasFullWidthFormActions())
                    ->sticky($this->areFormActionsSticky())
                    ->key('form-actions'),
            ]);
    }

    /**
     * @return array<Action>
     */
    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('保存')
                ->submit('save')
                ->keyBindings(['mod+s']),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        return $user->hasPermission(
            AdminPermissionRegistry::permissionName('site_settings', 'update'),
        );
    }
}
