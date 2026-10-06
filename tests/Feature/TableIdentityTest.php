<?php

namespace Termon\Ui\Tests\Feature;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\DomCrawler\Crawler;
use Termon\Ui\Tests\TestCase;

class TableIdentityTest extends TestCase
{
    public function test_sort_identity_preserves_other_table_state(): void
    {
        $this->app->instance('request', Request::create('/items?sort=title&direction=asc&users_sort=name&users_direction=asc'));

        $default = new Crawler(Blade::render('<x-ui::link-sort name="title">Title</x-ui::link-sort>'));
        parse_str(parse_url($default->filter('a')->attr('href'), PHP_URL_QUERY), $query);
        $this->assertSame('desc', $query['direction']);
        $this->assertSame('asc', $query['users_direction']);

        $items = new LengthAwarePaginator([1], 100, 10, 1, ['pageName' => 'users_page']);
        $users = new Crawler(Blade::render('<x-ui::link-sort :paginator="$items" name="name">Name</x-ui::link-sort>', compact('items')));
        parse_str(parse_url($users->filter('a')->attr('href'), PHP_URL_QUERY), $query);
        $this->assertSame('asc', $query['direction']);
        $this->assertSame('desc', $query['users_direction']);
    }

    public function test_size_form_resets_only_its_own_page(): void
    {
        $this->app->instance('request', Request::create('/items?page=3&size=10&users_page=2&users_size=25'));

        foreach (['default' => 'page', 'users' => 'users_page'] as $id => $pageName) {
            $items = new LengthAwarePaginator([1], 100, $id === 'default' ? 10 : 25, 2, [
                'path' => '/items', 'pageName' => $pageName,
            ]);
            $items->appends(request()->query());
            $html = new Crawler(Blade::render('<x-ui::paginator :items="$items" />', compact('items')));
            $sizeName = $id === 'default' ? 'size' : 'users_size';
            $otherPage = $id === 'default' ? 'users_page' : 'page';
            $this->assertSame($sizeName, $html->filter('select')->attr('name'));
            $this->assertSame($sizeName, $html->filter('label')->attr('for'));
            $this->assertSame(0, $html->filter('input[name="' . $pageName . '"]')->count());
            $this->assertSame(1, $html->filter('input[name="' . $otherPage . '"]')->count());
            $this->assertSame($id === 'default' ? '10' : '25', $html->filter('option[selected]')->attr('value'));
        }
    }
}
