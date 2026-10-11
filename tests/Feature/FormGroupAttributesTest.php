<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class FormGroupAttributesTest extends TestCase
{
    public function test_scalar_controls_and_groups_support_default_and_explicit_ids(): void
    {
        foreach (['input', 'select', 'textarea', 'toggle', 'range', 'date', 'datetime'] as $type) {
            foreach ([$type, $type.'-group'] as $component) {
                foreach ([null, 'modal-role'] as $id) {
                    $html = Blade::render('<x-ui::form.'.$component.' name="role" :id="$id" label="Role" wire:model="role" class="field" />', ['id' => $id]);
                    $crawler = new Crawler($html);
                    $expectedId = $id ?? 'role';
                    $control = $crawler->filter('input[id], select[id], textarea[id]');

                    $this->assertSame(1, $control->count(), $component);
                    $this->assertSame($expectedId, $control->attr('id'), $component);
                    $this->assertSame(1, $crawler->filter('[id]')->count(), $component);
                    $this->assertSame(1, substr_count($html, 'wire:model="role"'), $component);
                    $this->assertSame(1, substr_count($html, 'id="'.$expectedId.'"'), $component);
                    $this->assertGreaterThanOrEqual(1, $crawler->filter('[name="role"]')->count(), $component);
                    if (str_ends_with($component, '-group') || $type === 'toggle') {
                        $this->assertSame($expectedId, $crawler->filter('label[for]')->attr('for'), $component);
                    }
                }
            }
        }
    }

    public function test_group_id_override_keeps_validation_key_bound_to_name(): void
    {
        $errors = new \Illuminate\Support\ViewErrorBag;
        $errors->put('default', new \Illuminate\Support\MessageBag(['email' => 'Email is required.']));
        view()->share('errors', $errors);

        foreach (['input', 'select', 'textarea', 'toggle', 'range', 'date', 'datetime', 'otp'] as $type) {
            $html = Blade::render('<x-ui::form.'.$type.'-group name="email" id="modal-email" label="Email" />');
            $this->assertStringContainsString('Email is required.', $html, $type);
        }
    }

    public function test_repeated_names_can_use_distinct_ids(): void
    {
        $html = Blade::render('<x-ui::form.input-group name="email" label="Email" /><x-ui::form.input-group name="email" id="modal-email" label="Email" />');
        $crawler = new Crawler($html);

        $this->assertSame(['email', 'modal-email'], $crawler->filter('input')->extract(['id']));
        $this->assertSame(['email', 'modal-email'], $crawler->filter('label')->extract(['for']));
        $this->assertSame(['email', 'email'], $crawler->filter('input')->extract(['name']));
    }

    public function test_otp_ids_are_unique_and_group_label_targets_the_first_digit(): void
    {
        foreach (['otp', 'otp-group'] as $component) {
            foreach ([null, 'modal-code'] as $id) {
                $html = Blade::render('<x-ui::form.'.$component.' name="code" :id="$id" length="3" label="Code" />', ['id' => $id]);
                $crawler = new Crawler($html);
                $prefix = $id ?? 'code';

                $this->assertSame([$prefix.'-1', $prefix.'-2', $prefix.'-3'], $crawler->filter('input')->extract(['id']));
                $this->assertSame(['code[]', 'code[]', 'code[]'], $crawler->filter('input')->extract(['name']));
                $this->assertSame(3, $crawler->filter('[id]')->count());
                if ($component === 'otp-group') {
                    $this->assertSame($prefix.'-1', $crawler->filter('label')->attr('for'));
                }
            }
        }
    }

    public function test_checkbox_ids_and_group_prefixes_match_their_labels(): void
    {
        foreach ([null, 'modal-choice'] as $id) {
            $html = Blade::render('<x-ui::form.checkbox name="roles[]" value="admin" :id="$id" label="Admin" />', ['id' => $id]);
            $crawler = new Crawler($html);
            $this->assertSame($id ?? 'roles---admin', $crawler->filter('input')->attr('id'));
            $this->assertSame($crawler->filter('input')->attr('id'), $crawler->filter('label')->attr('for'));
        }

        $html = Blade::render('<x-ui::form.checkbox-group name="roles" id="modal-roles" label="Roles" :options="[\'admin\' => \'Admin\', \'editor\' => \'Editor\']" />');
        $crawler = new Crawler($html);
        $this->assertSame(['modal-roles-1', 'modal-roles-2'], $crawler->filter('input')->extract(['id']));
        $this->assertSame(['modal-roles-1', 'modal-roles-2'], $crawler->filter('label')->extract(['for']));
        $this->assertSame(['roles[]', 'roles[]'], $crawler->filter('input')->extract(['name']));
        $this->assertSame('Roles', trim($crawler->filter('fieldset > legend')->text()));
        $this->assertSame(2, $crawler->filter('[id]')->count());
    }

    public function test_array_field_names_are_preserved_with_explicit_ids(): void
    {
        $html = Blade::render('<x-ui::form.input-group name="items[0][title]" id="item-0-title" label="Title" required />');
        $crawler = new Crawler($html);

        $this->assertSame('items[0][title]', $crawler->filter('input')->attr('name'));
        $this->assertSame('item-0-title', $crawler->filter('input')->attr('id'));
        $this->assertSame('item-0-title', $crawler->filter('label')->attr('for'));
        $this->assertSame(1, $crawler->filter('[required]')->count());
        $this->assertSame(0, $crawler->filter('div[required]')->count());
    }

    public function test_file_input_does_not_render_a_value_attribute(): void
    {
        $html = Blade::render('<x-ui::form.input name="attachment" type="file" value="previous.pdf" class="upload" accept="application/pdf" disabled />');
        $input = (new Crawler($html))->filter('input');

        $this->assertFalse($input->getNode(0)->hasAttribute('value'));
        $this->assertSame('application/pdf', $input->attr('accept'));
        $this->assertTrue($input->getNode(0)->hasAttribute('disabled'));
        $this->assertContains('upload', explode(' ', $input->attr('class')));
    }

    public function test_select_zero_value_selects_the_option_instead_of_the_placeholder(): void
    {
        foreach (['select', 'select-group'] as $component) {
            foreach ([0, '0'] as $value) {
                $html = Blade::render('<x-ui::form.'.$component.' name="status" :options="$options" :value="$value" />', [
                    'options' => [0 => 'Inactive', 1 => 'Active'],
                    'value' => $value,
                ]);
                $selected = (new Crawler($html))->filter('option[selected]');

                $this->assertSame(1, $selected->count());
                $this->assertSame('0', $selected->attr('value'));
                $this->assertSame('Inactive', trim($selected->text()));
            }
        }
    }

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

        $this->assertSame(3, (new Crawler($html))->filter('input')->count());
        $this->assertStringNotContainsString('<x-ui::', $html);
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
