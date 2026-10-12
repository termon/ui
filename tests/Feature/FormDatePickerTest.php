<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\ViewErrorBag;
use Illuminate\Support\Facades\Validator;
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

}
