<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\DataProvider;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class ModalComponentTest extends TestCase
{
    public function test_modal_preserves_slots_attributes_and_accessible_title(): void
    {
        $html = Blade::render('<x-ui::modal name="edit" maxWidth="md" id="edit-dialog" class="custom-modal"><x-slot:title class="custom-title" id="edit-title">Edit record</x-slot:title><input name="record" autofocus><x-slot:footer><button type="button">Save</button></x-slot:footer></x-ui::modal>');
        $crawler = new Crawler($html);
        $dialog = $crawler->filter('dialog');

        $this->assertCount(1, $dialog);
        $this->assertSame('edit-dialog', $dialog->attr('id'));
        $this->assertSame('edit-title', $dialog->attr('aria-labelledby'));
        $this->assertSame('Edit record', $dialog->filter('#edit-title')->text());
        $this->assertStringContainsString('custom-title', $dialog->filter('#edit-title')->attr('class'));
        $this->assertStringContainsString('custom-modal', $dialog->attr('class'));
        $this->assertStringContainsString('sm:max-w-md', $dialog->attr('class'));
        $this->assertCount(1, $dialog->filter('input[autofocus]'));
        $this->assertSame(['Close modal', 'Save'], $dialog->filter('button')->each(fn (Crawler $button): string => $button->text()));
        $this->assertCount(0, $crawler->filter('dialog[open]'));
    }

    public function test_modal_generates_unique_labels_and_allows_an_explicit_label_without_a_title(): void
    {
        $html = Blade::render('<x-ui::modal name="first"><x-slot:title>First</x-slot:title></x-ui::modal><x-ui::modal name="second"><x-slot:title>Second</x-slot:title></x-ui::modal><x-ui::modal name="third" aria-label="Details">Content</x-ui::modal>');
        $dialogs = (new Crawler($html))->filter('dialog');
        $firstTitleId = $dialogs->eq(0)->attr('aria-labelledby');
        $secondTitleId = $dialogs->eq(1)->attr('aria-labelledby');

        $this->assertNotSame($firstTitleId, $secondTitleId);
        $this->assertSame('First', $dialogs->eq(0)->filter('[id="'.$firstTitleId.'"]')->text());
        $this->assertSame('Second', $dialogs->eq(1)->filter('[id="'.$secondTitleId.'"]')->text());
        $this->assertSame('Details', $dialogs->eq(2)->attr('aria-label'));
        $this->assertNull($dialogs->eq(2)->attr('aria-labelledby'));
        $this->assertStringContainsString('sm:max-w-2xl', $dialogs->eq(2)->attr('class'));
    }

    #[DataProvider('widths')]
    public function test_modal_supports_existing_widths(string $width): void
    {
        $html = Blade::render('<x-ui::modal name="sized" :maxWidth="$width">Content</x-ui::modal>', ['width' => $width]);

        $this->assertStringContainsString('sm:max-w-'.$width, (new Crawler($html))->filter('dialog')->attr('class'));
    }

    public function test_modal_defaults_to_non_dismissable_and_accepts_an_opt_in(): void
    {
        $html = Blade::render('<x-ui::modal name="default">Default</x-ui::modal><x-ui::modal name="enabled" :dismissable="true">Enabled</x-ui::modal>');
        $dialogs = (new Crawler($html))->filter('dialog');

        $this->assertStringContainsString('dismissable: false', $dialogs->eq(0)->attr('x-data'));
        $this->assertStringContainsString('dismissable: true', $dialogs->eq(1)->attr('x-data'));
        $this->assertNull($dialogs->eq(1)->attr('dismissable'));
    }

    public static function widths(): array
    {
        return array_map(fn (string $width): array => [$width], ['sm', 'md', 'lg', 'xl', '2xl']);
    }
}
