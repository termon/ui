<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Process\Process;
use Termon\Ui\Tests\TestCase;

class FormDatePickerTest extends TestCase
{
    public function test_new_pickers_render_and_initialize_without_scripts_or_global_factories(): void
    {
        foreach (['date' => '2026-10-12', 'datetime' => '2026-10-12 09:02:17'] as $type => $value) {
            $html = Blade::render('<x-ui::form.'.$type.' name="starts" :value="$value" wire:model.live="starts" required readonly form="booking" min="2026-01-01" />', compact('value'));
            $crawler = new Crawler($html);
            $wrapper = $crawler->filter('[x-modelable]');
            $this->assertSame(1, $wrapper->count());
            $this->assertSame('starts', $wrapper->attr('wire:model.live'));
            $this->assertSame(0, $crawler->filter('script')->count());
            $this->assertSame($value, $crawler->filter('input[type=hidden]')->attr('value'));
            $this->assertSame('booking', $crawler->filter('input[type=text]')->attr('form'));
            $this->assertFalse($crawler->filter('input[type=text]')->getNode(0)->hasAttribute('required'));
            $this->assertSame('true', $crawler->filter('input[type=text]')->attr('aria-required'));
            $this->assertTrue($crawler->filter('input[type=text]')->getNode(0)->hasAttribute('readonly'));
            $process = new Process(['node', '-e', <<<'JS'
const expression = require('node:fs').readFileSync(0, 'utf8');
const state = Function(`return (${expression})`)();
state.$refs = { input: { setCustomValidity() {} } };
state.sync();
require('node:assert/strict').equal(state.text, state.value);
JS]);
            $process->setInput($wrapper->attr('x-data'));
            $process->run();
            $this->assertTrue($process->isSuccessful(), $process->getErrorOutput());
        }
    }

    public function test_new_groups_forward_labels_errors_and_bindings(): void
    {
        foreach (['date', 'datetime'] as $type) {
            $html = Blade::render('<x-ui::form.'.$type.'-group name="starts" label="Starts" wire:model="starts" />');
            $crawler = new Crawler($html);
            $this->assertSame('starts', $crawler->filter('label')->first()->attr('for'));
            $this->assertSame('starts', $crawler->filter('[x-modelable]')->attr('wire:model'));
            $this->assertSame(1, substr_count($html, 'wire:model="starts"'));
        }
    }

    public function test_nested_laravel_errors_are_visible_and_invalid_values_are_preserved(): void
    {
        foreach (['date' => '2027-03-99', 'datetime' => '2027-03-99 09:00:00'] as $type => $value) {
            $validator = Validator::make(
                ['visit_windows' => ['visit_2' => ['opens_at' => $value]]],
                ['visit_windows.*.opens_at' => ['required', 'date_format:'.($type === 'date' ? 'Y-m-d' : 'Y-m-d H:i:s')]],
                [],
                ['visit_windows.visit_2.opens_at' => 'Second Visit Opens'],
            );
            $this->assertTrue($validator->fails());
            $errors = new ViewErrorBag;
            $errors->put('default', $validator->errors());
            view()->share('errors', $errors);
            $html = Blade::render('<x-ui::form.'.$type.'-group name="visit_windows[visit_2][opens_at]" id="second-opens" label="Second Visit Opens" aria-describedby="help" :value="$value" />', compact('value'));
            $crawler = new Crawler($html);
            $this->assertSame($value, $crawler->filter('input[type=hidden]')->attr('value'));
            $this->assertSame('visit_windows[visit_2][opens_at]', $crawler->filter('input[type=hidden]')->attr('name'));
            $this->assertSame('true', $crawler->filter('input[type=text]')->attr('aria-invalid'));
            $this->assertSame('help second-opens-error', $crawler->filter('input[type=text]')->attr('aria-describedby'));
            $this->assertSame($validator->errors()->first('visit_windows.visit_2.opens_at'), $crawler->filter('#second-opens-error')->text());
            $this->assertStringContainsString('Second Visit Opens', $crawler->filter('#second-opens-error')->text());
        }
    }

    public function test_livewire_bound_picker_initialization_does_not_change_with_its_value(): void
    {
        foreach (['date', 'datetime'] as $type) {
            foreach (['wire:model', 'wire:model.live', 'wire:model.blur'] as $binding) {
                $render = static fn (string $value): Crawler => new Crawler(Blade::render('<x-ui::form.'.$type.'-group name="starts" :value="$value" '.$binding.'="starts" />', compact('value')));
                $initial = $render('2026-10-12');
                $updated = $render('2026-11-15');
                $this->assertSame('starts', $initial->filter('[x-modelable]')->attr($binding));
                $this->assertSame($initial->filter('[x-modelable]')->attr('x-data'), $updated->filter('[x-modelable]')->attr('x-data'));
            }
        }
    }

    public function test_ordinary_blade_pickers_preserve_initial_values_and_have_a_valid_calendar_before_alpine_init(): void
    {
        foreach (['date' => '2026-10-12', 'datetime' => '2026-10-12 09:02:17'] as $type => $value) {
            $crawler = new Crawler(Blade::render('<x-ui::form.'.$type.'-group name="starts" :value="$value" />', compact('value')));
            $this->assertSame($value, $crawler->filter('input[type=hidden]')->attr('value'));
            $process = new Process(['node', '-e', <<<'JS'
const expression = require('node:fs').readFileSync(0, 'utf8');
const state = Function(`return (${expression})`)();
console.log(JSON.stringify({ value: state.value, text: state.text, month: state.month, title: state.title, cells: state.cells.length }));
JS]);
            $process->setInput($crawler->filter('[x-modelable]')->attr('x-data'));
            $process->mustRun();
            $calendar = json_decode($process->getOutput(), true, flags: JSON_THROW_ON_ERROR);
            $this->assertSame($value, $calendar['value']);
            $this->assertSame($value, $calendar['text']);
            $this->assertSame('2026-10', $calendar['month']);
            $this->assertSame('October 2026', $calendar['title']);
            $this->assertGreaterThanOrEqual(31, $calendar['cells']);
        }
    }
}
