<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Support\Facades\Blade;
use PHPUnit\Framework\Attributes\DataProvider;
use Termon\Ui\Tests\TestCase;

class StatisticComponentTest extends TestCase
{
    #[DataProvider('variants')]
    public function test_statistic_variant_renders(string $variant, string $expectedClass): void
    {
        $html = $this->renderBlade(
            '<x-ui::statistic title="Users" value="10" :variant="$variant" />',
            ['variant' => $variant],
        );

        $this->assertStringContainsString('Users', $html);
        $this->assertStringContainsString('10', $html);
        $this->assertStringContainsString($expectedClass, $html);
    }

    public function test_statistic_can_render_slot_value_and_icon(): void
    {
        $html = $this->renderBlade('<x-ui::statistic title="Users" icon="users">10 active</x-ui::statistic>');

        $this->assertStringContainsString('Users', $html);
        $this->assertStringContainsString('10 active', $html);
        $this->assertStringContainsString('<svg', $html);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function variants(): array
    {
        return [
            'blue' => ['blue', 'bg-blue-50'],
            'red' => ['red', 'bg-rose-50'],
            'rose' => ['rose', 'bg-rose-50'],
            'green' => ['green', 'bg-emerald-50'],
            'emerald' => ['emerald', 'bg-emerald-50'],
            'yellow' => ['yellow', 'bg-amber-50'],
            'pink' => ['pink', 'bg-pink-50'],
            'sky' => ['sky', 'bg-sky-50'],
            'indigo' => ['indigo', 'bg-indigo-50'],
            'gray' => ['gray', 'bg-gray-50'],
            'light' => ['light', 'bg-slate-50'],
            'slate' => ['slate', 'bg-slate-50'],
            'dark' => ['dark', 'bg-white'],
            'neutral' => ['neutral', 'bg-white'],
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function renderBlade(string $blade, array $data = []): string
    {
        $bufferLevel = ob_get_level();

        try {
            return Blade::render($blade, $data);
        } finally {
            while (ob_get_level() > $bufferLevel) {
                ob_end_clean();
            }
        }
    }
}
