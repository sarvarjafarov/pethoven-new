<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?string $title = 'Site Settings';

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->loadSettings());
    }

    protected function loadSettings(): array
    {
        $settings = Setting::all();
        $data = [];
        foreach ($settings as $s) {
            $key = $s->group . '_' . $s->key;
            $value = $s->value;
            // Try to decode JSON for repeater fields
            $decoded = json_decode($value, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data[$key] = $decoded;
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('general_footer_description')
                                    ->label('Footer Description')
                                    ->maxLength(500),
                                Forms\Components\TextInput::make('seo_meta_description')
                                    ->label('Default Meta Description')
                                    ->maxLength(300),
                                Forms\Components\TextInput::make('seo_meta_keywords')
                                    ->label('Default Meta Keywords')
                                    ->maxLength(300),
                            ]),

                        Forms\Components\Tabs\Tab::make('Contact')
                            ->schema([
                                Forms\Components\TextInput::make('contact_title')
                                    ->label('Contact Page Title')
                                    ->default('Get in touch'),
                                Forms\Components\Textarea::make('contact_description')
                                    ->label('Contact Page Description')
                                    ->rows(2),
                                Forms\Components\TextInput::make('contact_phone1')
                                    ->label('Phone Number 1')
                                    ->tel(),
                                Forms\Components\TextInput::make('contact_phone2')
                                    ->label('Phone Number 2')
                                    ->tel(),
                                Forms\Components\TextInput::make('contact_email1')
                                    ->label('Email 1')
                                    ->email(),
                                Forms\Components\TextInput::make('contact_email2')
                                    ->label('Email 2')
                                    ->email(),
                                Forms\Components\Textarea::make('contact_address')
                                    ->label('Address')
                                    ->rows(2),
                                Forms\Components\TextInput::make('contact_map_url')
                                    ->label('Google Maps Embed URL')
                                    ->url()
                                    ->columnSpanFull(),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Social Media')
                            ->schema([
                                Forms\Components\TextInput::make('social_twitter')
                                    ->label('Twitter / X URL')
                                    ->url(),
                                Forms\Components\TextInput::make('social_facebook')
                                    ->label('Facebook URL')
                                    ->url(),
                                Forms\Components\TextInput::make('social_pinterest')
                                    ->label('Pinterest URL')
                                    ->url(),
                                Forms\Components\TextInput::make('social_instagram')
                                    ->label('Instagram URL')
                                    ->url(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Home Page')
                            ->schema([
                                Forms\Components\TextInput::make('home_featured_title')
                                    ->label('Featured Section Title')
                                    ->default('Top sale'),
                                Forms\Components\Textarea::make('home_featured_description')
                                    ->label('Featured Section Description')
                                    ->rows(2),
                                Forms\Components\TextInput::make('home_blog_title')
                                    ->label('Blog Section Title')
                                    ->default('Blog posts'),
                                Forms\Components\Textarea::make('home_blog_description')
                                    ->label('Blog Section Description')
                                    ->rows(2),
                                Forms\Components\Repeater::make('home_categories')
                                    ->label('Homepage Categories')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\FileUpload::make('icon')
                                            ->image()
                                            ->disk(media_disk())
                                            ->directory('categories')
                                            ->visibility('public'),
                                        Forms\Components\ColorPicker::make('bg_color')
                                            ->label('Background Color'),
                                        Forms\Components\TextInput::make('link')
                                            ->url(),
                                        Forms\Components\TextInput::make('badge')
                                            ->helperText('e.g. "new", "sale" or leave empty'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull()
                                    ->defaultItems(0)
                                    ->reorderable(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Newsletter')
                            ->schema([
                                Forms\Components\TextInput::make('newsletter_title')
                                    ->label('Newsletter Title')
                                    ->default('Join with us'),
                                Forms\Components\Textarea::make('newsletter_description')
                                    ->label('Newsletter Description')
                                    ->rows(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('About Page')
                            ->schema([
                                Forms\Components\TextInput::make('about_subtitle')
                                    ->label('Page Subtitle'),
                                Forms\Components\Textarea::make('about_description')
                                    ->label('Page Description')
                                    ->rows(3),
                                Forms\Components\FileUpload::make('about_hero_image')
                                    ->label('Hero Title Image')
                                    ->image()
                                    ->disk(media_disk())
                                    ->directory('about')
                                    ->visibility('public'),
                                Forms\Components\FileUpload::make('about_hero_photo')
                                    ->label('Hero Photo')
                                    ->image()
                                    ->disk(media_disk())
                                    ->directory('about')
                                    ->visibility('public'),
                                Forms\Components\TextInput::make('about_section_title')
                                    ->label('Main Section Title'),
                                Forms\Components\Textarea::make('about_section_description')
                                    ->label('Main Section Description')
                                    ->rows(4),
                                Forms\Components\FileUpload::make('about_main_image')
                                    ->label('Main Section Image')
                                    ->image()
                                    ->disk(media_disk())
                                    ->directory('about')
                                    ->visibility('public'),
                                Forms\Components\Repeater::make('about_brand_logos')
                                    ->label('Brand Logos')
                                    ->schema([
                                        Forms\Components\FileUpload::make('image')
                                            ->image()
                                            ->disk(media_disk())
                                            ->directory('about')
                                            ->visibility('public'),
                                        Forms\Components\TextInput::make('alt')
                                            ->label('Alt Text')
                                            ->placeholder('Brand name'),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull()
                                    ->defaultItems(0)
                                    ->reorderable(),
                                Forms\Components\Repeater::make('about_funfacts')
                                    ->label('Fun Facts')
                                    ->schema([
                                        Forms\Components\TextInput::make('number')
                                            ->required()
                                            ->placeholder('e.g. 5000+'),
                                        Forms\Components\TextInput::make('label')
                                            ->required()
                                            ->placeholder('e.g. Clients'),
                                        Forms\Components\FileUpload::make('icon')
                                            ->image()
                                            ->disk(media_disk())
                                            ->directory('about')
                                            ->visibility('public'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull()
                                    ->defaultItems(0),
                                Forms\Components\Repeater::make('about_features')
                                    ->label('Features')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->required(),
                                        Forms\Components\Textarea::make('description')
                                            ->rows(2),
                                        Forms\Components\FileUpload::make('icon')
                                            ->image()
                                            ->disk(media_disk())
                                            ->directory('about')
                                            ->visibility('public'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull()
                                    ->defaultItems(0),
                            ]),

                        Forms\Components\Tabs\Tab::make('Blog Page')
                            ->schema([
                                Forms\Components\TextInput::make('blog_header_title')
                                    ->label('Blog Header Title')
                                    ->default('Whats the beauty secrets?'),
                                Forms\Components\Textarea::make('blog_header_description')
                                    ->label('Blog Header Description')
                                    ->rows(2),
                                Forms\Components\TextInput::make('blog_new_posts_title')
                                    ->label('New Posts Section Title')
                                    ->default('New Posts'),
                                Forms\Components\Textarea::make('blog_new_posts_description')
                                    ->label('New Posts Section Description')
                                    ->rows(2),
                                Forms\Components\TextInput::make('blog_others_title')
                                    ->label('Others Section Title')
                                    ->default('Others Posts'),
                                Forms\Components\Textarea::make('blog_others_description')
                                    ->label('Others Section Description')
                                    ->rows(2),
                                Forms\Components\FileUpload::make('blog_banner_image')
                                    ->label('Blog Page Banner')
                                    ->image()
                                    ->disk(media_disk())
                                    ->directory('blog')
                                    ->visibility('public'),
                            ]),

                        Forms\Components\Tabs\Tab::make('FAQ Page')
                            ->schema([
                                Forms\Components\TextInput::make('faq_title')
                                    ->label('FAQ Page Title')
                                    ->default('Frequently Questions'),
                                Forms\Components\Textarea::make('faq_description')
                                    ->label('FAQ Page Description')
                                    ->rows(2),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $fullKey => $value) {
            $parts = explode('_', $fullKey, 2);
            $group = $parts[0];
            $key = $parts[1] ?? $parts[0];

            if (is_array($value)) {
                $value = json_encode($value);
            }

            Setting::updateOrCreate(
                ['group' => $group, 'key' => $key],
                ['value' => $value]
            );
        }

        Setting::clearCache();

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Forms\Components\Actions\Action::make('save')
                ->label('Save Settings')
                ->submit('save'),
        ];
    }
}
