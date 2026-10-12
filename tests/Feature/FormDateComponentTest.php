<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class FormDateComponentTest extends TestCase
{
    public function test_date_component_binds_a_nullable_iso_value_separately_from_its_display(): void
    {
        $html = Blade::render('<x-ui::form.date name="starts_on" value="" wire:model="starts_on" />');

        $this->assertStringContainsString('x-modelable="value"', $html);
        $this->assertStringContainsString('wire:model="starts_on"', $html);
        $this->assertStringContainsString('type="hidden" name="starts_on" value="" :value="value"', $html);
        $this->assertStringContainsString('x-model="text"', $html);
        $this->assertStringContainsString('@click="clear()"', $html);
        $this->assertStringNotContainsString('<script>', $html);
    }

    public function test_date_component_preserves_an_initial_iso_value(): void
    {
        $html = Blade::render('<x-ui::form.date name="starts_on" value="2026-09-22" />');

        $this->assertStringContainsString('value="2026-09-22" :value="value"', $html);
        $expression = (new Crawler($html))->filter('[x-data]')->attr('x-data');
        $this->assertStringStartsWith('(function createDatePicker(options) {', $expression);
        $this->assertStringContainsString('2026-09-22', $expression);
    }
}
