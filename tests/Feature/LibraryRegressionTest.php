<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class LibraryRegressionTest extends TestCase
{
    public function test_untrusted_values_are_encoded_for_javascript(): void
    {
        foreach (["Owner's", "x', injected: (globalThis.uiInjected = true), tail: '"] as $value) {
            $examples = [
                '<x-ui::tabs :active="$value"><x-ui::tabs.tab :name="$value">Content</x-ui::tabs.tab></x-ui::tabs>',
                '<x-ui::form.range name="amount" :value="$value" />',
                '<x-ui::highchart :id="$value" config="{ series: [] }" />',
            ];
            foreach ($examples as $blade) {
                $html = Blade::render($blade, compact('value'));
                foreach ((new Crawler($html))->filter('[x-data]')->extract(['x-data']) as $expression) {
                    $this->assertStringNotContainsString($value, $expression);
                    $this->assertStringContainsString('\\u0027', $expression);
                }
            }
        }
    }

    public function test_empty_steps_render_with_and_without_percentage(): void
    {
        foreach ([false, true] as $percentage) {
            $html = Blade::render('<x-ui::steps :percentage="$percentage" />', compact('percentage'));
            $crawler = new Crawler($html);
            $this->assertSame(0, $crawler->filter('li')->count());
            if ($percentage) {
                $this->assertStringContainsString('0%', $crawler->text());
                $this->assertSame(1, $crawler->filter('[style="width: 0%;"]')->count());
            }
        }
        $html = Blade::render('<x-ui::steps :steps="[1 => [\'Start\', true], 2 => [\'Finish\', false]]" numbered percentage />');
        $this->assertSame(2, (new Crawler($html))->filter('li')->count());
        $this->assertStringContainsString('width: 50%;', $html);
    }

    public function test_page_size_form_round_trips_nested_filters_and_resets_only_its_own_page(): void
    {
        $this->app->instance('request', Request::create('/items?filter[status][]=active&filter[status][]=pending&filter[created][from]=2026-01-01&search=O%27Connor&page=3&users_page=2&size=10'));
        $items = new LengthAwarePaginator([1], 100, 10, 3, ['path' => '/items']);
        $html = Blade::render('<x-ui::paginator :items="$items" />', compact('items'));
        $form = (new Crawler($html, 'https://example.test/items'))->filter('form')->form(['size' => '25']);
        $this->assertSame([
            'filter' => ['status' => ['active', 'pending'], 'created' => ['from' => '2026-01-01']],
            'search' => "O'Connor",
            'users_page' => '2',
            'size' => '25',
        ], $form->getPhpValues());
        $this->assertSame('GET', $form->getMethod());
    }

    public function test_navigation_links_merge_custom_classes_with_existing_active_styles(): void
    {
        foreach (['navbar', 'sidebar'] as $layout) {
            $html = Blade::render('<x-ui::'.$layout.'.link href="/records" label="Records" class="host-class" id="host-link" :active="true" />');
            $link = (new Crawler($html))->filter('a');
            $this->assertSame('/records', $link->attr('href'));
            $this->assertSame('host-link', $link->attr('id'));
            foreach (['host-class', 'font-semibold', 'rounded-lg'] as $class) {
                $this->assertContains($class, explode(' ', $link->attr('class')));
            }
            preg_match('/<a\s[^>]*>/s', $html, $openingTag);
            $this->assertSame(1, preg_match_all('/(?:\s)class=/', $openingTag[0]));
        }
    }

    public function test_navigation_action_forms_and_dropdowns_forward_host_attributes(): void
    {
        foreach (['navbar', 'sidebar'] as $layout) {
            $html = Blade::render('<x-ui::'.$layout.'.form-link action="/logout" label="Logout" id="host-form" class="host-class" wire:submit="logout" data-host="action" />');
            $form = (new Crawler($html))->filter('form');
            $this->assertSame('host-form', $form->attr('id'));
            $this->assertSame('logout', $form->attr('wire:submit'));
            $this->assertSame('action', $form->attr('data-host'));
            $this->assertContains('host-class', explode(' ', $form->attr('class')));
            $this->assertSame('POST', $form->attr('method'));
            $this->assertSame('/logout', $form->attr('action'));

            $html = Blade::render('<x-ui::'.$layout.'.dropdown label="Menu" id="host-dropdown" class="host-class" data-host="menu">Content</x-ui::'.$layout.'.dropdown>');
            $crawler = new Crawler($html);
            $root = $crawler->filter('#host-dropdown');
            $this->assertSame('menu', $root->attr('data-host'));
            $this->assertContains('relative', explode(' ', $root->attr('class')));
            $this->assertContains('host-class', explode(' ', $root->attr('class')));
            $this->assertSame('button', $root->filter('button')->attr('type'));
            $this->assertSame('Menu', $root->filter('button')->attr('aria-label'));
            $this->assertSame('open', $root->filter('button')->attr(':aria-expanded'));
        }
    }

    public function test_sort_link_forwards_attributes_and_keeps_wrapper_classes(): void
    {
        $html = Blade::render('<x-ui::link-sort name="title" id="host-sort" class="host-class" aria-label="Sort titles" wire:navigate>Title</x-ui::link-sort>');
        $crawler = new Crawler($html);
        $link = $crawler->filter('a');
        $this->assertSame('host-sort', $link->attr('id'));
        $this->assertSame('Sort titles', $link->attr('aria-label'));
        $this->assertTrue($link->getNode(0)->hasAttribute('wire:navigate'));
        $this->assertContains('host-class', explode(' ', $crawler->filter('div')->attr('class')));
        parse_str(parse_url($link->attr('href'), PHP_URL_QUERY), $query);
        $this->assertSame('title', $query['sort']);
    }

    public function test_navigation_toggle_buttons_are_named_non_submit_controls(): void
    {
        foreach (['navbar', 'sidebar'] as $layout) {
            $crawler = new Crawler(Blade::render('<x-ui::'.$layout.' />'));
            $buttons = $crawler->filter('button');
            $this->assertGreaterThan(0, $buttons->count());
            foreach ($buttons as $button) {
                $this->assertSame('button', $button->getAttribute('type'));
                $this->assertNotSame('', $button->getAttribute('aria-label'));
                $this->assertTrue($button->hasAttribute(':aria-expanded'));
            }
        }
    }

    public function test_integer_heading_levels_keep_the_same_tag_and_classes_as_strings(): void
    {
        foreach (range(1, 6) as $level) {
            $integer = new Crawler(Blade::render('<x-ui::heading :level="$level">Title</x-ui::heading>', compact('level')));
            $literal = new Crawler(Blade::render('<x-ui::heading level="'.$level.'">Title</x-ui::heading>'));
            $this->assertSame('Title', $integer->filter('h'.$level)->text());
            $this->assertSame($literal->filter('h'.$level)->attr('class'), $integer->filter('h'.$level)->attr('class'));
        }
    }

    public function test_icons_emit_valid_size_and_shrink_classes_once(): void
    {
        foreach (['' => '5', 'mini' => '4', 'micro' => '3'] as $variant => $size) {
            $html = Blade::render('<x-ui::icon icon="home" :variant="$variant" class="host-icon" />', compact('variant'));
            $classes = explode(' ', (new Crawler($html))->filter('svg')->attr('class'));
            foreach (['shrink-0', 'w-'.$size, 'h-'.$size, 'host-icon'] as $class) {
                $this->assertSame(1, count(array_filter($classes, fn ($token) => $token === $class)));
            }
            $this->assertNotContains('shrink-0w-'.$size, $classes);
        }
    }

    public function test_calendar_days_are_native_buttons_and_inputs_have_keyboard_open_handlers(): void
    {
        foreach (['date', 'datetime'] as $type) {
            $html = Blade::render('<x-ui::form.'.$type.' name="starts" />');
            $this->assertStringContainsString('<button type="button" data-calendar-day', $html);
            $crawler = new Crawler($html);
            $this->assertSame(1, $crawler->filter('button[data-calendar-day][type="button"]')->count());
            $input = $crawler->filter('input[type="text"]');
            foreach (['enter', 'space', 'arrow-down'] as $key) {
                $this->assertSame('openCalendar()', $input->attr('x-on:keydown.'.$key.'.prevent'));
            }
            // Calendar content sits in an Alpine template, so check its source too.
            $this->assertStringContainsString(':aria-pressed="!!isSelectedDate(d)"', $html);
            $this->assertStringContainsString('focus-visible:ring-2', $html);
        }
    }
}
