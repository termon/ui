<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use Illuminate\View\ViewException;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class FormConfirmComponentTest extends TestCase
{
    public function test_confirmation_preserves_the_original_form_and_escapes_the_message(): void
    {
        $html = Blade::render(
            '<form method="POST" action="/delete"><input type="hidden" name="_method" value="DELETE"><x-ui::form.confirm :message="$message" variant="link" icon="trash" data-testid="delete-trigger">Delete</x-ui::form.confirm></form>',
            ['message' => 'Delete <script>alert(1)</script>?'],
        );
        $crawler = new Crawler($html);

        $this->assertCount(1, $crawler->filter('form'));
        $this->assertSame('/delete', $crawler->filter('form')->attr('action'));
        $this->assertSame('DELETE', $crawler->filter('input[name="_method"]')->attr('value'));
        $this->assertCount(1, $crawler->filter('form dialog[role="alertdialog"]'));
        $this->assertCount(0, $crawler->filter('dialog[open], script, button[type="submit"]'));
        $this->assertSame('dialog', $crawler->filter('[data-testid="delete-trigger"]')->attr('aria-haspopup'));
        $this->assertSame('Delete <script>alert(1)</script>?', $crawler->filter('dialog p')->text());
        $this->assertSame(['Yes', 'No'], $crawler->filter('dialog button')->each(fn (Crawler $button): string => $button->text()));
        $this->assertCount(1, $crawler->filter('dialog button[autofocus]'));
    }

    public function test_confirmation_uses_defaults_and_supports_multiple_instances(): void
    {
        $html = Blade::render('<x-ui::form.confirm>Delete</x-ui::form.confirm><x-ui::form.confirm message="Remove this item?" confirm-variant="dark">Remove</x-ui::form.confirm>');
        $crawler = new Crawler($html);

        $this->assertCount(2, $crawler->filter('dialog'));
        $this->assertSame(['Are you sure?', 'Remove this item?'], $crawler->filter('dialog p')->each(fn (Crawler $message): string => $message->text()));
        $this->assertStringContainsString('bg-gray-900', $crawler->filter('dialog')->eq(1)->filter('button')->first()->attr('class'));
    }
    public function test_form_mode_does_not_add_livewire_loading_or_ignore_attributes(): void
    {
        $html = Blade::render('<x-ui::form.confirm mode="form" data-testid="trigger">Delete</x-ui::form.confirm>');
        $crawler = new Crawler($html);
        $trigger = $crawler->filter('[data-testid="trigger"]');

        $this->assertStringContainsString('livewire: false', $crawler->filter('[data-confirmation]')->attr('x-data'));
        $this->assertNull($trigger->attr('wire:loading.attr'));
        $this->assertNull($trigger->attr('wire:target'));
        $this->assertNull($crawler->filter('dialog')->attr('wire:ignore.self'));
    }

    public function test_livewire_mode_renders_action_configuration_and_loading_targets(): void
    {
        $html = Blade::render('<x-ui::form.confirm mode="livewire" confirming-property="confirming" prepare-action="prepare" confirm-action="destroy" cancel-action="cancel" data-testid="trigger">Delete</x-ui::form.confirm>');
        $crawler = new Crawler($html);
        $trigger = $crawler->filter('[data-testid="trigger"]');

        $this->assertSame('disabled', $trigger->attr('wire:loading.attr'));
        $this->assertSame('prepare,destroy,cancel', $trigger->attr('wire:target'));
        $this->assertNotNull($crawler->filter('dialog')->attr('wire:ignore.self'));
        $this->assertStringContainsString('livewire: true', $crawler->filter('[data-confirmation]')->attr('x-data'));
        foreach (['confirming', 'prepare', 'destroy', 'cancel'] as $value) {
            $this->assertStringContainsString($value, $crawler->filter('[data-confirmation]')->attr('x-data'));
        }
    }

    public function test_livewire_mode_allows_an_explicit_loading_target(): void
    {
        $html = Blade::render('<x-ui::form.confirm mode="livewire" confirming-property="confirming" prepare-action="prepare" confirm-action="destroy" cancel-action="cancel" target="destroy,refresh" data-testid="trigger">Delete</x-ui::form.confirm>');

        $this->assertSame('destroy,refresh', (new Crawler($html))->filter('[data-testid="trigger"]')->attr('wire:target'));
    }

    #[DataProvider('invalidConfigurations')]
    public function test_invalid_modes_and_incomplete_livewire_configuration_are_rejected(string $attributes, string $message): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage($message);
        Blade::render('<x-ui::form.confirm '.$attributes.'>Delete</x-ui::form.confirm>');
    }

    public static function invalidConfigurations(): array
    {
        $cases = [['mode="invalid"', 'Confirmation mode must be form or livewire.']];
        $required = ['confirming-property' => 'confirming', 'prepare-action' => 'prepare', 'confirm-action' => 'destroy', 'cancel-action' => 'cancel'];
        foreach (array_keys($required) as $missing) {
            $attributes = 'mode="livewire"';
            foreach ($required as $name => $value) {
                if ($name !== $missing) $attributes .= ' '.$name.'="'.$value.'"';
            }
            $cases[] = [$attributes, 'Livewire confirmation requires a confirming property and prepare, confirm, and cancel action names.'];
        }
        return $cases;
    }

}
