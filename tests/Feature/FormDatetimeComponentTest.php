<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Termon\Ui\Tests\TestCase;

class FormDatetimeComponentTest extends TestCase
{
    public function test_datetime_component_supports_nullable_values_and_livewire_binding(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui::form.datetime
                name="visible_from"
                value=""
                wire:model="visible_from"
            />
        BLADE);

        $this->assertStringContainsString('x-modelable="value"', $html);
        $this->assertStringContainsString('wire:model="visible_from"', $html);
        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('name="visible_from"', $html);
        $this->assertStringContainsString('value=""', $html);
        $this->assertStringContainsString('x-bind:value="value"', $html);
        $this->assertStringContainsString('placeholder="Select date and time"', $html);
        $this->assertStringContainsString('x-on:click="clear"', $html);
        $this->assertStringNotContainsString('name="visible_from" value="" type="text"', $html);
    }

    public function test_datetime_component_submits_a_canonical_value_separate_from_the_display_input(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui::form.datetime
                name="scheduled_at"
                value="2026-06-26 09:30:00"
            />
        BLADE);

        $this->assertStringContainsString('type="hidden"', $html);
        $this->assertStringContainsString('name="scheduled_at"', $html);
        $this->assertStringContainsString('value="2026-06-26 09:30:00"', $html);
        $this->assertStringContainsString('x-model="displayValue"', $html);
        $this->assertStringContainsString('formatValue(date)', $html);
        $this->assertStringContainsString('formatDateTime(date)', $html);
    }

    public function test_datetime_group_forwards_livewire_binding_to_datetime_component(): void
    {
        view()->share('errors', new ViewErrorBag);

        $html = Blade::render(<<<'BLADE'
            <x-ui::form.datetime-group
                name="report_opens_at"
                label="Upload Opens"
                wire:model="report_opens_at"
            />
        BLADE);

        $this->assertStringContainsString('Upload Opens', $html);
        $this->assertStringContainsString('x-modelable="value"', $html);
        $this->assertStringContainsString('wire:model="report_opens_at"', $html);
        $this->assertStringContainsString('name="report_opens_at"', $html);
        $this->assertSame(1, substr_count($html, 'wire:model="report_opens_at"'));
    }
}
