<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class ComponentCompatibilityTest extends TestCase
{
    public function test_existing_form_submits_the_same_nested_values(): void
    {
        $html = Blade::render(<<<'BLADE'
            <form action="/profile" method="post">
                <x-ui::form.input-group name="profile[name]" label="Name" value="O'Connor & Sons" />
                <x-ui::form.textarea-group name="profile[bio]" label="Biography">Existing biography</x-ui::form.textarea-group>
                <x-ui::form.select-group name="role" label="Role" :options="['admin' => 'Admin', 'editor' => 'Editor']" value="editor" />
                <x-ui::form.checkbox-group name="roles" label="Roles" :options="['admin' => 'Admin', 'editor' => 'Editor', 'viewer' => 'Viewer']" :value="['admin', 'editor']" />
                <x-ui::form.toggle-group name="published" label="Published" checked />
                <x-ui::form.toggle name="featured" />
                <x-ui::form.date-group name="starts_on" label="Starts" value="2026-10-11" />
                <x-ui::form.datetime-group name="starts_at" label="Time" value="2026-10-11 09:30:00" />
                <x-ui::button type="submit">Save</x-ui::button>
            </form>
        BLADE);
        $crawler = new Crawler($html, 'https://example.test/profile');
        // DomCrawler indexes controls by name and otherwise lets an unchecked
        // checkbox mask its successful hidden fallback. Browsers omit that checkbox.
        $submission = new Crawler($html, 'https://example.test/profile');
        foreach ($submission->filter('input[type="checkbox"]:not([checked])') as $unchecked) {
            $unchecked->parentNode->removeChild($unchecked);
        }
        $form = $submission->selectButton('Save')->form();

        $this->assertSame('POST', $form->getMethod());
        $this->assertSame('https://example.test/profile', $form->getUri());
        $this->assertSame([
            'profile' => ['name' => "O'Connor & Sons", 'bio' => "Existing biography\n"],
            'role' => 'editor',
            'roles' => ['admin', 'editor'],
            'published' => '1',
            'featured' => '0',
            'starts_on' => '2026-10-11',
            'starts_at' => '2026-10-11 09:30:00',
        ], $form->getPhpValues());
        foreach (['profile[name]', 'profile[bio]', 'role', 'published', 'starts_on', 'starts_at'] as $id) {
            $this->assertSame(1, $crawler->filter('[id="'.$id.'"]')->count());
            $this->assertSame(1, $crawler->filter('label[for="'.$id.'"]')->count());
        }
        $this->assertSame(['roles---admin', 'roles---editor', 'roles---viewer'], $crawler->filter('input[type="checkbox"][name="roles[]"]')->extract(['id']));
    }

    public function test_explicit_ids_change_selectors_without_changing_posted_keys(): void
    {
        $html = Blade::render('<form method="post"><x-ui::form.input-group name="email" id="modal-email" label="Email" value="alice@example.test" /><x-ui::form.checkbox name="roles[]" id="modal-admin" value="admin" checked /><x-ui::button type="submit">Save</x-ui::button></form>');
        $crawler = new Crawler($html, 'https://example.test/profile');
        $this->assertSame(['email' => 'alice@example.test', 'roles' => ['admin']], $crawler->selectButton('Save')->form()->getPhpValues());
        $this->assertSame('modal-email', $crawler->filter('label')->first()->attr('for'));
        $this->assertSame(1, $crawler->filter('#modal-email')->count());
        $this->assertSame(1, $crawler->filter('#modal-admin')->count());
    }

    #[DataProvider('scalarValues')]
    public function test_literal_and_bound_scalar_values_remain_equivalent(string $component, string $value): void
    {
        $attributes = $component === 'select' ? ' :options="$options"' : '';
        $data = ['value' => $value, 'options' => [$value => 'Selected']];
        $literal = Blade::render('<x-ui::form.'.$component.' name="field" value="'.$value.'"'.$attributes.' />', $data);
        $bound = Blade::render('<x-ui::form.'.$component.' name="field" :value="$value"'.$attributes.' />', $data);
        foreach ([$literal, $bound] as $html) {
            $control = (new Crawler($html))->filter('input, select, textarea');
            $this->assertSame('field', $control->attr('id'));
            $this->assertSame('field', $control->attr('name'));
            $actual = match ($component) {
                'textarea' => trim($control->text()),
                'select' => $control->filter('option[selected]')->attr('value'),
                default => $control->attr('value'),
            };
            $this->assertSame($value, $actual);
        }
    }

    public static function scalarValues(): array
    {
        $cases = [];
        foreach (['input', 'textarea', 'select'] as $component) {
            foreach (['0', 'Alice', 'Zoë & Sons', "O'Connor"] as $value) {
                $cases[$component.'-'.$value] = [$component, $value];
            }
        }
        return $cases;
    }

    public function test_livewire_modifiers_and_native_attributes_stay_on_the_control(): void
    {
        foreach (['input', 'textarea', 'select', 'toggle'] as $type) {
            foreach (['wire:model', 'wire:model.live', 'wire:model.defer', 'wire:model.lazy'] as $binding) {
                $html = Blade::render('<x-ui::form.'.$type.'-group name="field" label="Field" '.$binding.'="form.field" required data-hook="field" aria-describedby="help" class="host-wrapper" />');
                $crawler = new Crawler($html, 'https://example.test/profile');
                $control = $crawler->filter('input[id], textarea, select');
                $this->assertSame('form.field', $control->attr($binding));
                $this->assertSame(1, substr_count($html, $binding.'="form.field"'));
                $this->assertSame('field', $control->attr('data-hook'));
                $this->assertSame('help', $control->attr('aria-describedby'));
                $this->assertTrue($control->getNode(0)->hasAttribute('required'));
                $this->assertStringContainsString('host-wrapper', $crawler->filter('div')->first()->attr('class'));
                $this->assertSame(0, $crawler->filter('[aria-invalid]')->count());
                $this->assertSame(0, $crawler->filter('#field-error')->count());
            }
        }
    }

    public function test_file_input_keeps_upload_contract(): void
    {
        $html = Blade::render('<form method="post" enctype="multipart/form-data"><x-ui::form.input-group type="file" name="attachments[]" label="Attachments" multiple accept="application/pdf" required /><x-ui::button type="submit">Upload</x-ui::button></form>');
        $crawler = new Crawler($html, 'https://example.test/profile');
        $input = $crawler->filter('input');
        $this->assertSame('file', $input->attr('type'));
        $this->assertSame('attachments[]', $input->attr('name'));
        $this->assertSame('attachments[]', $input->attr('id'));
        $this->assertSame('application/pdf', $input->attr('accept'));
        $this->assertTrue($input->getNode(0)->hasAttribute('multiple'));
        $this->assertTrue($input->getNode(0)->hasAttribute('required'));
        $this->assertArrayHasKey('attachments', $crawler->selectButton('Upload')->form()->getPhpFiles());
    }

    public function test_legacy_variants_match_their_semantic_aliases(): void
    {
        $aliases = ['blue' => 'primary', 'red' => 'danger', 'green' => 'success', 'yellow' => 'warning', 'oblue' => 'outline-primary', 'ored' => 'outline-danger', 'ogreen' => 'outline-success', 'oyellow' => 'outline-warning'];
        foreach (['button', 'link'] as $component) {
            foreach ($aliases as $old => $new) {
                $render = fn ($variant) => (new Crawler(Blade::render('<x-ui::'.$component.' :variant="$variant" label="Action" />', compact('variant'))))->filter($component === 'button' ? 'button' : 'a');
                $this->assertSame($render($old)->attr('class'), $render($new)->attr('class'));
                $this->assertSame('Action', $render($old)->text());
            }
        }
    }

    public function test_host_icon_override_and_variant_alias_still_work(): void
    {
        view()->addLocation(__DIR__.'/../Fixtures/views');
        foreach (['icon="check"', 'variant="check"'] as $prop) {
            $crawler = new Crawler(Blade::render('<x-ui::svg '.$prop.' id="host-icon" class="host-size" viewBox="0 0 16 16" fill="currentColor" />'));
            $svg = $crawler->filter('svg');
            $this->assertSame(1, $svg->filter('path[data-host-icon="check"]')->count());
            $this->assertSame('host-icon', $svg->attr('id'));
            $this->assertSame('0 0 16 16', $svg->attr('viewBox'));
            $this->assertSame('currentColor', $svg->attr('fill'));
            $this->assertStringContainsString('host-size', $svg->attr('class'));
        }
    }

    public function test_navigation_action_links_preserve_post_csrf_and_method_spoofing(): void
    {
        session()->put('_token', 'compatibility-csrf-token');
        foreach (['navbar', 'sidebar'] as $layout) {
            foreach (['post', 'PUT', 'DELETE'] as $method) {
                $html = Blade::render('<x-ui::'.$layout.'.form-link action="/records/42" :method="$method" icon="trash" label="Delete" />', compact('method'));
                $crawler = new Crawler($html, 'https://example.test/records');
                $form = $crawler->filter('form')->form();
                $this->assertSame('POST', $form->getMethod());
                $this->assertSame('https://example.test/records/42', $form->getUri());
                $this->assertSame(['_token' => 'compatibility-csrf-token', '_method' => $method], $form->getPhpValues());
                $this->assertSame('submit', $crawler->filter('button')->attr('type'));
                $this->assertStringContainsString('Delete', $crawler->filter('button')->text());
            }
        }
    }

    public function test_layout_and_table_named_slots_keep_host_content_and_selectors(): void
    {
        foreach (['navbar', 'sidebar'] as $layout) {
            $html = Blade::render('<x-ui::'.$layout.' id="host-layout"><x-slot:brandTitle>Host brand</x-slot:brandTitle><x-slot:navigation><x-ui::'.$layout.'.link href="/records" label="Records" icon="folder" :active="true" /></x-slot:navigation><x-slot:toolbar><button type="button" id="host-tool">Host tool</button></x-slot:toolbar> Host content</x-ui::'.$layout.'>');
            $crawler = new Crawler($html);
            $this->assertSame(1, $crawler->filter('#host-layout')->count());
            $this->assertSame(1, $crawler->filter('#host-tool')->count());
            $this->assertStringContainsString('Host brand', $crawler->text());
            foreach ($crawler->filter('a[href="/records"]') as $link) {
                $this->assertStringContainsString('Records', $link->textContent);
                $this->assertStringContainsString('font-semibold', $link->getAttribute('class'));
            }
            $this->assertGreaterThan(0, $crawler->filter('a[href="/records"]')->count());
            if ($layout === 'sidebar') {
                $this->assertStringContainsString('Host content', $crawler->filter('main')->text());
            }
        }

        $html = Blade::render('<x-ui::card id="host-card"><x-slot:header id="host-header">Header</x-slot:header><x-ui::table id="host-table" table-class="host-table-class"><x-slot:thead id="host-head"><tr><x-ui::table.th scope="row" id="host-column">Name</x-ui::table.th></tr></x-slot:thead><x-slot:tbody id="host-body"><x-ui::table.tr id="host-row"><x-ui::table.td id="host-cell">Alice</x-ui::table.td></x-ui::table.tr></x-slot:tbody><x-slot:tfoot id="host-foot"><tr><td>Summary</td></tr></x-slot:tfoot></x-ui::table><x-slot:footer id="host-footer">Footer</x-slot:footer></x-ui::card>');
        $crawler = new Crawler($html);
        foreach (['host-card', 'host-header', 'host-table', 'host-head', 'host-body', 'host-column', 'host-row', 'host-cell', 'host-foot', 'host-footer'] as $id) {
            $this->assertSame(1, $crawler->filter('#'.$id)->count(), $id);
        }
        $this->assertSame('Alice', $crawler->filter('#host-cell')->text());
        $this->assertSame('row', $crawler->filter('#host-column')->attr('scope'));
        $this->assertStringContainsString('host-table-class', $crawler->filter('table')->attr('class'));
    }

    public function test_chart_and_legacy_highchart_config_formats_remain_supported(): void
    {
        $cases = [
            ['chart', ':config="$config"', '', '\\u0022type\\u0022:\\u0022bar\\u0022'],
            ['chart', '', "{ type: 'bar', data: { labels: [], datasets: [] } }", "type: 'bar'"],
            ['highchart', 'config="{ series: [] }"', '', '{ series: [] }'],
            ['highchart', '', '{ series: [] }', '{ series: [] }'],
        ];
        foreach ($cases as [$component, $props, $slot, $marker]) {
            $html = Blade::render('<x-ui::'.$component.' id="legacy-chart" '.$props.' class="host-chart">'.$slot.'</x-ui::'.$component.'>', [
                'config' => ['type' => 'bar', 'data' => ['labels' => [], 'datasets' => []]],
            ]);
            $crawler = new Crawler($html);
            $this->assertSame(1, $crawler->filter('#legacy-chart')->count());
            $this->assertSame(1, $crawler->filter('.host-chart')->count());
            $this->assertStringContainsString($marker, $crawler->filter('[x-data]')->attr('x-data'));
        }
    }
}
