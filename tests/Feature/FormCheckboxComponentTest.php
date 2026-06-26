<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;
use Termon\Ui\Tests\TestCase;

class FormCheckboxComponentTest extends TestCase
{
    public function test_checkbox_renders_a_native_checkbox_without_hidden_fallback(): void
    {
        $html = Blade::render(<<<'BLADE'
            <x-ui::form.checkbox
                name="roles[]"
                value="admin"
                label="Administrator"
                description="Can manage everything."
                :checked="true"
                wire:model="roles"
            />
        BLADE);

        $this->assertStringContainsString('type="checkbox"', $html);
        $this->assertStringContainsString('name="roles[]"', $html);
        $this->assertStringContainsString('value="admin"', $html);
        $this->assertStringContainsString('checked', $html);
        $this->assertStringContainsString('wire:model="roles"', $html);
        $this->assertStringContainsString('Administrator', $html);
        $this->assertStringContainsString('Can manage everything.', $html);
        $this->assertStringNotContainsString('type="hidden"', $html);
    }

    public function test_checkbox_group_renders_multiple_options_for_the_same_array_field(): void
    {
        view()->share('errors', new ViewErrorBag);

        $html = Blade::render(<<<'BLADE'
            <x-ui::form.checkbox-group
                name="roles"
                label="Roles"
                description="Assign one or more roles."
                :value="['admin', 'editor']"
                :options="[
                    'admin' => 'Administrator',
                    'editor' => ['label' => 'Editor', 'description' => 'Can update content.'],
                    'viewer' => ['label' => 'Viewer', 'disabled' => true],
                ]"
                wire:model="roles"
                variant="card"
            />
        BLADE);

        $this->assertSame(3, substr_count($html, 'type="checkbox"'));
        $this->assertSame(3, substr_count($html, 'name="roles[]"'));
        $this->assertSame(2, substr_count($html, 'checked'));
        $this->assertStringContainsString('value="admin"', $html);
        $this->assertStringContainsString('value="editor"', $html);
        $this->assertStringContainsString('value="viewer"', $html);
        $this->assertStringContainsString('disabled', $html);
        $this->assertStringContainsString('wire:model="roles"', $html);
        $this->assertStringContainsString('Roles', $html);
        $this->assertStringContainsString('Assign one or more roles.', $html);
        $this->assertStringContainsString('Can update content.', $html);
        $this->assertStringNotContainsString('type="hidden"', $html);
    }
}
