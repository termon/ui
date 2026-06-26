<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\DataProvider;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Termon\Ui\Tests\TestCase;

class ComponentSmokeTest extends TestCase
{
    #[DataProvider('componentExamples')]
    public function test_component_renders(string $component, string $blade, ?string $expected = null): void
    {
        $html = $this->renderBlade($blade, $this->bladeData($component));

        $this->assertIsString($html, "Component [{$component}] did not render a string.");

        if ($expected !== null) {
            $this->assertStringContainsString($expected, $html, "Component [{$component}] did not render the expected marker.");
        }
    }

    public function test_all_public_components_have_smoke_examples(): void
    {
        $this->assertSame(
            $this->componentNames(),
            array_keys(self::componentExamples())
        );
    }

    #[DataProvider('icons')]
    public function test_icon_renders_through_svg_component(string $icon): void
    {
        $html = $this->renderBlade('<x-ui::svg :icon="$icon" />', [
            'icon' => $icon,
        ]);

        $this->assertStringContainsString('<svg', $html);
    }

    /**
     * @return array<string, array{0: string, 1: string, 2?: string|null}>
     */
    public static function componentExamples(): array
    {
        return [
            'accordion' => ['accordion', '<x-ui::accordion open="one"><x-ui::accordion.item name="one" title="One">Panel</x-ui::accordion.item></x-ui::accordion>', 'Panel'],
            'accordion.item' => ['accordion.item', '<div x-data="{ open: \'one\' }"><x-ui::accordion.item name="one" title="One">Panel</x-ui::accordion.item></div>', 'Panel'],
            'avatar' => ['avatar', '<x-ui::avatar size="sm" />', '<svg'],
            'badge' => ['badge', '<x-ui::badge>Approved</x-ui::badge>', 'Approved'],
            'breadcrumb' => ['breadcrumb', '<x-ui::breadcrumb :crumbs="[\'Home\' => \'/\', \'Page\' => \'/page\']" />', 'Home'],
            'button' => ['button', '<x-ui::button icon="trash">Delete</x-ui::button>', 'Delete'],
            'card' => ['card', '<x-ui::card><x-slot name="header">Card Header</x-slot>Card Body<x-slot name="footer">Card Footer</x-slot></x-ui::card>', 'Card Body'],
            'chart' => ['chart', '<x-ui::chart id="sales-chart" :config="[\'type\' => \'bar\', \'data\' => [\'labels\' => [], \'datasets\' => []]]" />', 'sales-chart'],
            'display' => ['display', '<x-ui::display label="Name" value="Alice" icon="user" />', 'Alice'],
            'divider' => ['divider', '<x-ui::divider>Section</x-ui::divider>', 'Section'],
            'flash' => ['flash', '@php(session()->flash(\'status\', \'Saved\')) <x-ui::flash />', 'Saved'],
            'form.checkbox' => ['form.checkbox', '<x-ui::form.checkbox name="agree" label="Agree" checked />', 'Agree'],
            'form.checkbox-group' => ['form.checkbox-group', '<x-ui::form.checkbox-group name="roles" label="Roles" :options="[\'admin\' => \'Administrator\']" :value="[\'admin\']" />', 'Administrator'],
            'form.confirm' => ['form.confirm', '<form><x-ui::form.confirm icon="trash">Delete</x-ui::form.confirm></form>', 'Delete'],
            'form.date' => ['form.date', '<x-ui::form.date name="starts_on" value="2026-06-26" />', 'starts_on'],
            'form.date-group' => ['form.date-group', '<x-ui::form.date-group name="starts_on" label="Starts" value="2026-06-26" />', 'Starts'],
            'form.datetime' => ['form.datetime', '<x-ui::form.datetime name="starts_at" value="2026-06-26 09:00:00" />', 'starts_at'],
            'form.datetime-group' => ['form.datetime-group', '<x-ui::form.datetime-group name="starts_at" label="Starts" value="2026-06-26 09:00:00" />', 'Starts'],
            'form.error' => ['form.error', '<x-ui::form.error>Required.</x-ui::form.error>', 'Required.'],
            'form.input' => ['form.input', '<x-ui::form.input name="title" value="Hello" />', 'Hello'],
            'form.input-group' => ['form.input-group', '<x-ui::form.input-group name="title" label="Title" value="Hello" />', 'Title'],
            'form.label' => ['form.label', '<x-ui::form.label for="title" icon="tag">Title</x-ui::form.label>', 'Title'],
            'form.otp' => ['form.otp', '<x-ui::form.otp name="code" length="2" />', 'code[]'],
            'form.otp-group' => ['form.otp-group', '<x-ui::form.otp-group name="code" label="Code" length="2" />', 'Code'],
            'form.range' => ['form.range', '<x-ui::form.range name="score" value="3" min="1" max="5" />', 'score'],
            'form.range-group' => ['form.range-group', '<x-ui::form.range-group name="score" label="Score" value="3" min="1" max="5" />', 'Score'],
            'form.select' => ['form.select', '<x-ui::form.select name="role" :options="[\'admin\' => \'Admin\']" value="admin" />', 'Admin'],
            'form.select-group' => ['form.select-group', '<x-ui::form.select-group name="role" label="Role" :options="[\'admin\' => \'Admin\']" value="admin" />', 'Role'],
            'form.textarea' => ['form.textarea', '<x-ui::form.textarea name="bio">Biography</x-ui::form.textarea>', 'Biography'],
            'form.textarea-group' => ['form.textarea-group', '<x-ui::form.textarea-group name="bio" label="Bio" value="Biography" />', 'Biography'],
            'form.toggle' => ['form.toggle', '<x-ui::form.toggle name="published" label="Published" checked />', 'Published'],
            'form.toggle-group' => ['form.toggle-group', '<x-ui::form.toggle-group name="published" label="Published" checked />', 'Published'],
            'header' => ['header', '<x-ui::header>Header</x-ui::header>', 'Header'],
            'heading' => ['heading', '<x-ui::heading level="2">Heading</x-ui::heading>', 'Heading'],
            'hero' => ['hero', '<x-ui::hero heading="Hero" subheading="Sub">Action</x-ui::hero>', 'Hero'],
            'highchart' => ['highchart', '<x-ui::highchart id="profit-chart" config="{ series: [] }" />', 'profit-chart'],
            'icon' => ['icon', '<x-ui::icon icon="trash" />', '<svg'],
            'link' => ['link', '<x-ui::link href="/home" icon="home">Home</x-ui::link>', 'Home'],
            'link-sort' => ['link-sort', '<x-ui::link-sort name="title">Title</x-ui::link-sort>', 'Title'],
            'modal' => ['modal', '<x-ui::modal name="delete" show><x-slot name="title">Confirm</x-slot>Modal Body<x-slot name="footer">Footer</x-slot></x-ui::modal>', 'Modal Body'],
            'modal.trigger' => ['modal.trigger', '<x-ui::modal.trigger for="delete">Open</x-ui::modal.trigger>', 'Open'],
            'navbar' => ['navbar', '<x-ui::navbar><x-slot:brandTitle>Brand</x-slot:brandTitle><x-slot:navigation><x-ui::navbar.link href="/" icon="home" label="Home" /></x-slot:navigation></x-ui::navbar>', 'Brand'],
            'navbar.dropdown' => ['navbar.dropdown', '<x-ui::navbar.dropdown icon="folder" label="Menu"><x-ui::navbar.link href="/" icon="home" label="Home" /></x-ui::navbar.dropdown>', 'Menu'],
            'navbar.form-link' => ['navbar.form-link', '<x-ui::navbar.form-link action="/logout" icon="exit" label="Logout" />', 'Logout'],
            'navbar.link' => ['navbar.link', '<x-ui::navbar.link href="/" icon="home" label="Home" />', 'Home'],
            'paginator' => ['paginator', '<x-ui::paginator :items="$items" />', 'Page 1 of 1'],
            'rating' => ['rating', '<x-ui::rating value="3" />', '<svg'],
            'sidebar' => ['sidebar', '<x-ui::sidebar><x-slot:brandTitle>Brand</x-slot:brandTitle><x-slot:navigation><x-ui::sidebar.link href="/" icon="home" label="Home" /></x-slot:navigation>Content</x-ui::sidebar>', 'Content'],
            'sidebar.dropdown' => ['sidebar.dropdown', '<x-ui::sidebar.dropdown icon="folder" label="Menu"><x-ui::sidebar.link href="/" icon="home" label="Home" /></x-ui::sidebar.dropdown>', 'Menu'],
            'sidebar.form-link' => ['sidebar.form-link', '<x-ui::sidebar.form-link action="/logout" icon="exit" label="Logout" />', 'Logout'],
            'sidebar.link' => ['sidebar.link', '<x-ui::sidebar.link href="/" icon="home" label="Home" />', 'Home'],
            'statistic' => ['statistic', '<x-ui::statistic title="Users" value="10" icon="users" />', 'Users'],
            'steps' => ['steps', '<x-ui::steps :steps="[1 => [\'Start\', true], 2 => [\'Done\', false]]" numbered />', 'Start'],
            'svg' => ['svg', '<x-ui::svg icon="trash" />', '<svg'],
            'table' => ['table', '<x-ui::table><x-slot:thead><tr><x-ui::table.th>Name</x-ui::table.th></tr></x-slot:thead><x-slot:tbody><x-ui::table.tr><x-ui::table.td>Alice</x-ui::table.td></x-ui::table.tr></x-slot:tbody></x-ui::table>', 'Alice'],
            'table.td' => ['table.td', '<table><tbody><tr><x-ui::table.td>Cell</x-ui::table.td></tr></tbody></table>', 'Cell'],
            'table.th' => ['table.th', '<table><thead><tr><x-ui::table.th>Heading</x-ui::table.th></tr></thead></table>', 'Heading'],
            'table.tr' => ['table.tr', '<table><tbody><x-ui::table.tr><td>Row</td></x-ui::table.tr></tbody></table>', 'Row'],
            'tabs' => ['tabs', '<x-ui::tabs active="One"><x-ui::tabs.tab name="One">Panel</x-ui::tabs.tab></x-ui::tabs>', 'Panel'],
            'tabs.tab' => ['tabs.tab', '<div x-data="{ activeTab: \'One\' }"><x-ui::tabs.tab name="One">Panel</x-ui::tabs.tab></div>', 'Panel'],
            'title' => ['title', '<x-ui::title>Title</x-ui::title>', 'Title'],
        ];
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function icons(): array
    {
        $icons = [];

        foreach (glob(__DIR__.'/../../src/resources/views/components/icons/*.blade.php') ?: [] as $file) {
            $icon = basename($file, '.blade.php');
            $icons[$icon] = [$icon];
        }

        ksort($icons);

        return $icons;
    }

    /**
     * @return array<int, string>
     */
    private function componentNames(): array
    {
        $componentsPath = realpath(__DIR__.'/../../src/resources/views/components');
        $components = [];

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($componentsPath));

        foreach ($iterator as $file) {
            if (! $file instanceof SplFileInfo || $file->isDir() || $file->getExtension() !== 'php') {
                continue;
            }

            $component = $this->componentName($componentsPath, $file);

            if (str_starts_with($component, 'icons.')) {
                continue;
            }

            $components[] = $component;
        }

        sort($components);

        return $components;
    }

    private function componentName(string $componentsPath, SplFileInfo $file): string
    {
        $relativePath = str($file->getRealPath())
            ->after($componentsPath.DIRECTORY_SEPARATOR)
            ->beforeLast('.blade.php')
            ->replace(DIRECTORY_SEPARATOR, '.')
            ->value();

        return str($relativePath)->replaceEnd('.index', '')->value();
    }

    /**
     * @return array<string, mixed>
     */
    protected function bladeData(string $component): array
    {
        if ($component !== 'paginator') {
            return [];
        }

        return [
            'items' => new LengthAwarePaginator(collect([1]), 1, 10, 1, ['path' => '/items']),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderBlade(string $blade, array $data = []): string
    {
        $bufferLevel = ob_get_level();

        try {
            return Blade::render($blade, $data);
        } finally {
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }
        }
    }
}
