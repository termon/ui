<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Termon\Ui\Tests\TestCase;

class FormGroupAttributesTest extends TestCase
{
    public function test_select_group_places_binding_only_on_the_select(): void
    {
        $html = Blade::render('<x-ui::form.select-group name="role" label="Role" :options="[\'admin\' => \'Admin\']" wire:model="role" required class="field" />');

        $this->assertSame(1, substr_count($html, 'wire:model="role"'));
        $this->assertMatchesRegularExpression('/<select[^>]*wire:model="role"[^>]*required/s', $html);
        $this->assertStringContainsString('class="w-full field"', $html);
    }

    public function test_date_group_places_binding_only_on_the_picker(): void
    {
        $html = Blade::render('<x-ui::form.date-group name="starts_on" label="Start" wire:model="starts_on" />');

        $this->assertSame(1, substr_count($html, 'wire:model="starts_on"'));
        $this->assertMatchesRegularExpression('/<div x-data="datePicker[^>]*wire:model="starts_on"/s', $html);
    }

    public function test_otp_forwards_attributes_and_binds_each_digit_to_an_array_index(): void
    {
        $html = Blade::render('<x-ui::form.otp name="code" length="3" wire:model="code" required inputmode="numeric" />');

        $this->assertSame(3, substr_count($html, 'name="code[]"'));
        foreach (range(0, 2) as $index) {
            $this->assertStringContainsString('wire:model="code.'.$index.'"', $html);
        }
        $this->assertSame(3, substr_count($html, 'inputmode="numeric"'));
        $this->assertSame(3, substr_count($html, 'required="required"'));

        $groupHtml = Blade::render('<x-ui::form.otp-group name="code" length="2" wire:model.live="code" />');
        $this->assertSame(1, substr_count($groupHtml, 'wire:model.live="code.0"'));
        $this->assertSame(1, substr_count($groupHtml, 'wire:model.live="code.1"'));
        $this->assertSame(2, substr_count($groupHtml, 'wire:model.live='));
    }

    public function test_range_limits_ticks_and_respects_step_count(): void
    {
        $wide = Blade::render('<x-ui::form.range name="amount" min="0" max="100000" step="1" />');
        $short = Blade::render('<x-ui::form.range name="amount" min="0" max="10" step="5" />');

        $this->assertSame(11, substr_count($wide, 'bg-neutral-400'));
        $this->assertSame(3, substr_count($short, 'bg-neutral-400'));
    }
}
