<?php

namespace Termon\Ui\Tests\Feature;

use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Termon\Ui\Tests\TestCase;

class LivewireDatePickerTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }

    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), LivewireServiceProvider::class];
    }

    public function test_invalid_nested_dates_display_server_errors_and_allow_a_corrected_retry(): void
    {
        Livewire::test(DatePickerHost::class)
            ->set('visits.visit_2.opens_at', '2027-03-99')
            ->set('visits.visit_2.closes_at', '2027-03-99 09:00:00')
            ->call('save')
            ->assertHasErrors(['visits.visit_2.opens_at' => 'date_format', 'visits.visit_2.closes_at' => 'date_format'])
            ->assertSee('Second Visit Opens')
            ->assertSee('Second Visit Target')
            ->assertSeeHtml('aria-invalid="true"')
            ->assertSeeHtml('value="2027-03-99"')
            ->assertSet('saved', false)
            ->set('visits.visit_2.opens_at', '2027-03-12')
            ->set('visits.visit_2.closes_at', '2027-03-13 09:02:17')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSet('saved', true)
            ->assertSeeHtml('value="2027-03-13 09:02:17"');
    }

    public function test_empty_fields_use_laravel_required_errors(): void
    {
        Livewire::test(DatePickerHost::class)
            ->set('visits.visit_2.opens_at', '')
            ->call('save')
            ->assertHasErrors(['visits.visit_2.opens_at' => 'required'])
            ->assertSee('Second Visit Opens')
            ->assertSet('saved', false);
    }
}

class DatePickerHost extends Component
{
    public array $visits = ['visit_2' => ['opens_at' => '2027-03-12', 'closes_at' => '2027-03-13 09:02:17']];
    public bool $saved = false;

    public function save(): void
    {
        $this->validate([
            'visits.visit_2.opens_at' => ['required', 'date_format:Y-m-d'],
            'visits.visit_2.closes_at' => ['required', 'date_format:Y-m-d H:i:s', 'after:visits.visit_2.opens_at'],
        ], [], ['visits.visit_2.opens_at' => 'Second Visit Opens', 'visits.visit_2.closes_at' => 'Second Visit Target']);
        $this->saved = true;
    }

    public function render(): string
    {
        return <<<'BLADE'
<div>
    <x-ui::form.date-group name="visits[visit_2][opens_at]" label="Second Visit Opens" :value="$visits['visit_2']['opens_at']" wire:model="visits.visit_2.opens_at" required />
    <x-ui::form.datetime-group name="visits[visit_2][closes_at]" label="Second Visit Target" :value="$visits['visit_2']['closes_at']" wire:model="visits.visit_2.closes_at" required />
    <button wire:click="save">Save</button>
</div>
BLADE;
    }
}
