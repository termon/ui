<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class FormRegressionTest extends TestCase
{
    public function test_blank_select_values_do_not_select_the_zero_option(): void
    {
        foreach (['select', 'select-group'] as $component) {
            foreach ([null, ''] as $value) {
                $html = Blade::render('<x-ui::form.'.$component.' name="status" :options="[0 => \'Inactive\', 1 => \'Active\']" :value="$value" />', compact('value'));
                $selected = (new Crawler($html))->filter('option[selected]');
                $this->assertSame(1, $selected->count());
                $this->assertSame('', $selected->attr('value'));
            }
        }
    }

    public function test_multiple_select_restores_array_values_including_zero(): void
    {
        foreach (['select', 'select-group'] as $component) {
            foreach ([['0', 'editor'], [0, 'editor'], []] as $value) {
                $html = Blade::render('<x-ui::form.'.$component.' name="roles[]" multiple :options="[0 => \'Guest\', \'admin\' => \'Admin\', \'editor\' => \'Editor\']" :value="$value" />', compact('value'));
                $crawler = new Crawler($html);
                $this->assertSame($value === [] ? [] : ['0', 'editor'], $crawler->filter('option[selected]')->extract(['value']));
                $this->assertSame('roles[]', $crawler->filter('select')->attr('name'));
            }
        }
    }

    public function test_disabled_toggles_disable_the_hidden_fallback_too(): void
    {
        foreach (['toggle', 'toggle-group'] as $component) {
            foreach ([false, true] as $disabled) {
                $html = Blade::render('<x-ui::form.'.$component.' name="enabled" :checked="true" :disabled="$disabled" form="settings" />', compact('disabled'));
                $inputs = (new Crawler($html))->filter('input');
                $this->assertSame(['0', '1'], $inputs->extract(['value']));
                $this->assertSame(['settings', 'settings'], $inputs->extract(['form']));
                $this->assertSame($disabled ? 2 : 0, $inputs->filter('[disabled]')->count());
            }
        }
    }

    public function test_checkbox_group_ids_handle_sanitization_collisions(): void
    {
        $html = Blade::render('<x-ui::form.checkbox-group name="roles" :options="$options" />', [
            'options' => ['a b' => 'First', 'a-b' => 'Second', 'a-b-2' => 'Third', 'admin' => 'Admin'],
        ]);
        $crawler = new Crawler($html);
        $ids = $crawler->filter('input')->extract(['id']);
        $this->assertCount(4, array_unique($ids));
        $this->assertSame('roles---a-b', $ids[0]);
        $this->assertSame('roles---admin', $ids[3]);
        $this->assertSame($ids, $crawler->filter('label')->extract(['for']));
        $this->assertSame(['a b', 'a-b', 'a-b-2', 'admin'], $crawler->filter('input')->extract(['value']));
    }

    public function test_picker_attributes_target_fields_while_livewire_stays_on_wrapper(): void
    {
        foreach (['date', 'datetime', 'date-group', 'datetime-group'] as $component) {
            $html = Blade::render('<x-ui::form.'.$component.' name="starts_on" id="modal-start" disabled required form="booking" placeholder="Choose" aria-describedby="hint" wire:model="starts_on" class="field" />');
            $crawler = new Crawler($html);
            $visible = $crawler->filter('input[type="text"]');
            $hidden = $crawler->filter('input[type="hidden"]');
            $this->assertSame(1, $visible->filter('[disabled][required]')->count(), $component);
            $this->assertSame(1, $hidden->filter('[disabled]')->count(), $component);
            $this->assertSame('booking', $visible->attr('form'));
            $this->assertSame('booking', $hidden->attr('form'));
            $this->assertSame('Choose', $visible->attr('placeholder'));
            $this->assertSame('hint', $visible->attr('aria-describedby'));
            $this->assertSame(0, $crawler->filter('div[disabled], div[required], div[aria-describedby]')->count());
            $this->assertSame(1, $crawler->filter('div[wire\\:model]')->count());
            $this->assertSame(0, $crawler->filter('input[wire\\:model]')->count());
        }
    }

    public function test_required_picker_is_not_barred_from_constraint_validation(): void
    {
        foreach (['date', 'datetime'] as $component) {
            $html = Blade::render('<x-ui::form.'.$component.' name="starts_on" required />');
            $input = (new Crawler($html))->filter('input[type="text"]');
            $this->assertTrue($input->getNode(0)->hasAttribute('required'));
            $this->assertFalse($input->getNode(0)->hasAttribute('readonly'));
            $this->assertTrue($input->getNode(0)->hasAttribute('x-on:beforeinput.prevent'));
            $default = new Crawler(Blade::render('<x-ui::form.'.$component.' name="starts_on" />'));
            $this->assertTrue($default->filter('input[type="text"]')->getNode(0)->hasAttribute('readonly'));
        }
    }

    public function test_group_errors_reference_resolved_ids_and_preserve_help_text(): void
    {
        $errors = new ViewErrorBag;
        $errors->put('default', new MessageBag(['field' => 'Invalid value.']));
        view()->share('errors', $errors);
        foreach (['input', 'select', 'textarea', 'toggle', 'range', 'date', 'datetime', 'otp', 'checkbox'] as $type) {
            foreach ([null, 'modal-field'] as $id) {
                $options = in_array($type, ['select', 'checkbox'], true) ? ' :options="[\'a\' => \'A\', \'b\' => \'B\']"' : '';
                $html = Blade::render('<x-ui::form.'.$type.'-group name="field" :id="$id" aria-describedby="help"'.$options.' />', compact('id'));
                $crawler = new Crawler($html);
                $errorId = ($id ?? 'field').'-error';
                $error = $crawler->filter('[id="'.$errorId.'"]');
                $this->assertSame(1, $error->count(), $type);
                $this->assertSame('Invalid value.', trim($error->text()));
                $controls = $crawler->filter('input[id], select[id], textarea[id]');
                $this->assertGreaterThan(0, $controls->count());
                foreach ($controls as $control) {
                    $this->assertSame('help '.$errorId, $control->getAttribute('aria-describedby'), $type);
                    $this->assertSame('true', $control->getAttribute('aria-invalid'), $type);
                }
                $this->assertFalse($error->getNode(0)->hasAttribute('aria-describedby'));
            }
        }
    }

    public function test_error_component_keeps_standalone_and_slot_usage(): void
    {
        $errors = new ViewErrorBag;
        $errors->put('default', new MessageBag(['email' => 'Invalid email.']));
        view()->share('errors', $errors);
        $crawler = new Crawler(Blade::render('<x-ui::form.error for="email" />'));
        $this->assertSame('Invalid email.', $crawler->filter('#email-error')->text());
        $crawler = new Crawler(Blade::render('<x-ui::form.error id="custom-error">Custom message</x-ui::form.error>'));
        $this->assertSame('Custom message', $crawler->filter('#custom-error')->text());
    }
}
