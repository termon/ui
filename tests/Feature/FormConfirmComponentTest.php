<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Termon\Ui\Tests\TestCase;

class FormConfirmComponentTest extends TestCase
{
    public function test_confirm_component_submits_the_wrapping_form_through_submit_events(): void
    {
        $html = Blade::render(<<<'BLADE'
            <form wire:submit="deleteDocument(1)">
                <x-ui::form.confirm variant="ored" message="Delete this document?">Delete</x-ui::form.confirm>
            </form>
        BLADE);

        $this->assertStringContainsString('wire:submit="deleteDocument(1)"', $html);
        $this->assertStringContainsString('Delete this document?', $html);
        $this->assertStringContainsString('requestSubmit()', $html);
        $this->assertStringContainsString('.submit()', $html);
        $this->assertStringContainsString('Yes', $html);
        $this->assertStringContainsString('No', $html);
    }

    public function test_confirm_component_accepts_an_optional_icon(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui::form.confirm icon="trash">Delete</x-ui::form.confirm>
        BLADE);

        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('shrink-0', $html);
        $this->assertStringContainsString('Delete', $html);
    }
}
