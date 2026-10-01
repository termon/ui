<?php

namespace Termon\Ui\Tests\Feature;

use Livewire\Component;
use Livewire\Livewire;
use Livewire\LivewireServiceProvider;
use Termon\Ui\Tests\TestCase;

class LivewireFormConfirmComponentTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
    }

    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), LivewireServiceProvider::class];
    }

    public function test_prepare_and_confirm_preserve_the_host_component_and_execute_its_action(): void
    {
        Livewire::test(ConfirmationHost::class)
            ->assertSet('confirming', false)
            ->assertSee('Delete record 42?')
            ->assertSeeHtml('wire:ignore.self')
            ->assertSeeHtml('wire:target="prepareDelete,deleteRecord,cancelDelete"')
            ->call('prepareDelete')
            ->assertSet('confirming', true)
            ->assertSet('deletions', 0)
            ->call('deleteRecord')
            ->assertSet('confirming', false)
            ->assertSet('deletions', 1)
            ->assertSet('recordId', 42);
    }

    public function test_cancellation_does_not_perform_the_destructive_action_and_can_be_reopened(): void
    {
        Livewire::test(ConfirmationHost::class)
            ->call('prepareDelete')
            ->call('cancelDelete')
            ->assertSet('confirming', false)
            ->assertSet('deletions', 0)
            ->call('prepareDelete')
            ->assertSet('confirming', true);
    }

    public function test_server_validation_keeps_confirmation_open_and_allows_a_corrected_retry(): void
    {
        Livewire::test(ConfirmationHost::class)
            ->call('prepareDelete')
            ->set('reason', '')
            ->call('deleteRecord')
            ->assertHasErrors(['reason' => 'required'])
            ->assertSet('confirming', true)
            ->assertSet('deletions', 0)
            ->set('reason', 'Duplicate record')
            ->call('deleteRecord')
            ->assertHasNoErrors()
            ->assertSet('confirming', false)
            ->assertSet('deletions', 1);
    }
}

class ConfirmationHost extends Component
{
    public bool $confirming = false;
    public int $recordId = 42;
    public int $deletions = 0;
    public string $reason = 'Duplicate record';

    public function prepareDelete(): void
    {
        $this->confirming = true;
    }

    public function deleteRecord(): void
    {
        $this->validate(['reason' => 'required']);
        $this->deletions++;
        $this->confirming = false;
    }

    public function cancelDelete(): void
    {
        $this->confirming = false;
    }

    public function render(): string
    {
        return <<<'BLADE'
            <div>
                <input wire:model="reason">
                <x-ui::form.confirm mode="livewire" confirming-property="confirming"
                    prepare-action="prepareDelete" confirm-action="deleteRecord" cancel-action="cancelDelete"
                    :message="'Delete record '.$recordId.'?'">Delete</x-ui::form.confirm>
            </div>
        BLADE;
    }
}
