# Laravel View Components

> **Version 1.8.47**

A simple set of anonymous Laravel Blade View Components using TailwindCSS 4 for styling, to help construct basic user interfaces. 

## Installation

The package is not available on `packagist` therefore you must add the `github` repository to your composer.json file

```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/termon/ui"
    }
],
```

Now, use composer to install the library.

```
$ composer require termon/ui
```

### Publish Components Locally

If you would prefer to add the components directly into your applications resources folder they can be published using

```
 php artisan vendor:publish --tag=termon/ui
```

> Note you will need to re-publish these components when the base package is updated via composer

## Prerequisite

These components require installation of [Tailwind CSS](https://tailwindcss.com) for styling and [AlpineJS](https:://alpinejs.dev) for interactivity.

### Tailwind 4 Configuration 
Add the package Blade views to your Tailwind sources and include the recommended dark mode + Alpine `x-cloak` helpers in `app.css`.

```css
@import 'tailwindcss';

@source '../../vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php';
@source '../../vendor/termon/ui/src/resources/views/**/*.blade.php';
@source '../../storage/framework/views/*.php';
@source '../**/*.blade.php';
@source '../**/*.js';

@theme {
    --font-sans: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji',
        'Segoe UI Symbol', 'Noto Color Emoji';
}

/* tailwindcss class based dark mode */
@custom-variant dark (&:where(.dark, .dark *));

/* alpinejs stop flicker when toggling dark mode / hidden UI */
[x-cloak] { display: none !important; }
```

Notes:
- `@custom-variant dark ...` enables class-based dark mode support used by the components.
- `[x-cloak]` prevents Alpine-powered UI (dropdowns, modals, pickers) from flashing before Alpine initializes.

### AlpineJS Setup

You can use AlpineJS either via CDN or through Livewire.

#### Option 1: AlpineJS via CDN

Include AlpineJS in your main layout (typically before `</body>`):

```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

#### Option 2: AlpineJS via Livewire

If you are using Livewire (v4), AlpineJS is included by default. In that case, include Livewire assets in your layout and you do not need a separate Alpine CDN script.

```blade
<head>
    @livewireStyles
</head>
<body>
    {{ $slot ?? '' }}

    @livewireScripts
</body>
```

## Using Components

The component prefix is `x-ui` followed by the name of the component (separated by :: or . when installed locally )

```
<x-ui::<component-name> // using vendor package
<x-ui.<component-name>  // when published locally 
```

### Passing Dynamic Values

Use bound attributes for dynamic component values:

```blade
<x-ui::display label="Surname" :value="$staff->surname" />

<x-ui::form.input-group
    name="name"
    label="Name"
    :value="old('name', $user->name)"
/>
```

Avoid rendering dynamic values into component attributes:

```blade
{{-- Avoid this for dynamic values --}}
<x-ui::display label="Surname" value="{{ $staff->surname }}" />

<x-ui::form.input-group
    name="name"
    label="Name"
    value="{{ old('name', $user->name) }}"
/>
```

Blade escapes `{{ ... }}` before the component receives the prop. If the value contains HTML-significant characters such as apostrophes, ampersands, or angle brackets, passing it as `value="{{ ... }}"` can result in already-escaped text being escaped again by the component output. That can display entity text such as `O&amp;#039;Connor` instead of `O&#039;Connor`.

Using `:value="..."` passes the original PHP value to the component. The component then performs the final Blade escaping at render time, so values like `O'Connor & Sons <script>` display correctly while remaining safe.

## Testing

The package includes a PHPUnit/Testbench test suite with Livewire integration tests for `form.confirm`. Livewire is a development dependency for these tests. Run the suite from the package root:

```
composer test
```

Run the JavaScript handler regression tests separately (requires Node.js). These cover normal form submission and Livewire action handling, including validation, retries, busy state, cancellation, and focus callbacks. They execute component handlers with test doubles; they do not replace browser tests for native dialog behavior:

```sh
composer test:js
```

## Available Components

Component groups currently provided by the package:

- Layout/navigation: `navbar`, `navbar.link`, `navbar.dropdown`, `navbar.form-link`, `sidebar`, `sidebar.link`, `sidebar.dropdown`, `sidebar.form-link`, `nav-tabs`, `nav-tabs.link`, `header`, `hero`
- Content/display: `heading`, `title`, `divider`, `card`, `display`, `statistic`, `badge`, `chip`, `breadcrumb`, `avatar`, `rating`, `steps`, `section-header`, `resource-row`
- Disclosure/overlays: `accordion`, `accordion.item`, `tabs`, `tabs.tab`, `modal`, `modal.trigger`, `flash`
- Tables/pagination: `table`, `table.tr`, `table.th`, `table.td`, `link-sort`, `paginator`
- Forms: `form.input`, `form.input-group`, `form.select`, `form.select-group`, `form.textarea`, `form.textarea-group`, `form.date`, `form.date-group`, `form.datetime`, `form.datetime-group`, `form.checkbox`, `form.checkbox-group`, `form.toggle`, `form.toggle-group`, `form.range`, `form.range-group`, `form.otp`, `form.otp-group`, `form.label`, `form.error`, `form.confirm`
- Charts/icons: `chart`, `highchart`, `svg`, `icon`

### Heading

The heading component provides styled headings with consistent light/dark mode styling and mobile support. The component takes a level parameter with a value between `1-6`

```
<x-ui::heading level="1">Heading Level 1</x-ui::heading>
<x-ui::heading level="2">Heading Level 2</x-ui::heading>
```
### Divider

The `divider` component renders a horizontal divider with inline slot content. It accepts a `type` prop with values `top` or `bottom` and defaults to `top`.

```
<x-ui::divider>
    <x-ui::heading level="2">Section Title</x-ui::heading>
</x-ui::divider>

<x-ui::divider type="bottom">
    <span class="text-sm text-gray-500">End of section</span>
</x-ui::divider>
```

Available divider types:
- `top` adds a bottom border with spacing below the content.
- `bottom` adds a top border with spacing above the content.

Additional HTML attributes and classes can be passed to the wrapper `div`.

### Chart

The `chart` component renders a Chart.js 4.5.1 chart from a PHP array config. It applies light/dark theme defaults for text, grid lines, tooltip colours, and the chart-area background. Animations are disabled automatically when the browser requests reduced motion.

The component accepts these props:

- `id` (required): the canvas ID.
- `config`: a serializable Chart.js configuration. A JavaScript configuration can be supplied through the default slot instead.
- `aria-label`: an accessible name for the canvas. It defaults to a headline generated from `id`.
- `fallback-text`: text exposed when the canvas cannot be rendered. It defaults to the accessible label.

Pie and doughnut charts receive overridable presentation defaults: a bottom/start-aligned legend, circular legend markers, additional legend padding, rounded segments, segment spacing, and an offset for the hovered segment.

```blade
<x-ui::chart
    id="student-engagement"
    aria-label="Student engagement classification breakdown"
    fallback-text="Student engagement classifications and percentages."
    :config="[
        'type' => 'doughnut',
        'data' => [
            'labels' => ['PP (12)', '00 (4)'],
            'datasets' => [[
                'label' => 'Student Engagement',
                'data' => [12, 4],
                'backgroundColor' => ['#16a34a', '#f59e0b'],
            ]],
        ],
    ]"
    class="h-96"
/>
```

### Serializable tooltip options

Chart.js tooltip callbacks cannot be passed in a PHP array. The component provides serializable alternatives under `options.plugins.tooltip`:

- `labelMap`: replaces a visible chart label with a more descriptive tooltip label.
- `datasetLabelMap`: replaces the tooltip dataset label without changing the legend.
- `valueMap`: replaces the plotted value, keyed first by dataset label and then chart label.
- `displayValue`: set to `false` to display only the tooltip label rather than repeating a value already present in the title.
- `secondaryValueMap`: adds a second tooltip line. It may be keyed directly by chart label or nested under a dataset label.

The component converts these options into a Chart.js tooltip callback and removes the custom keys before rendering.

```blade
<x-ui::chart
    id="student-engagement"
    :config="[
        'type' => 'doughnut',
        'data' => [
            'labels' => ['PP (12)', '00 (4)'],
            'datasets' => [[
                'label' => 'Student Engagement',
                'data' => [12, 4],
                'backgroundColor' => ['#16a34a', '#f59e0b'],
            ]],
        ],
        'options' => [
            'plugins' => [
                'tooltip' => [
                    'displayValue' => false,
                    'labelMap' => [
                        'PP (12)' => 'PP - Placed',
                        '00 (4)' => '00 - Unplaced',
                    ],
                    'datasetLabelMap' => [
                        'Student Engagement' => 'Engagement',
                    ],
                    'valueMap' => [
                        'Student Engagement' => [
                            'PP (12)' => '75%',
                            '00 (4)' => '25%',
                        ],
                    ],
                    'secondaryValueMap' => [
                        'PP (12)' => '12 students',
                        '00 (4)' => '4 students',
                    ],
                ],
                'centreText' => [
                    'text' => '16',
                    'subtext' => 'students',
                ],
            ],
            'cutout' => '58%',
        ],
    ]"
    class="h-96"
/>
```

### Doughnut centre text

Set `options.plugins.centreText.text` to draw a summary in the middle of a doughnut. The following keys are supported:

- `text` (required to enable the centre text)
- `subtext`
- `color` and `subtextColor`
- `fontSize` (default `24`) and `subtextFontSize` (default `12`)

### Segment click events

Set `options.emitOnClick` to a browser event name. Clicking a chart element dispatches a bubbling `CustomEvent` from the component root. Its `detail` contains `dataIndex`, `datasetIndex`, `label`, and `value`.

```blade
<div x-on:engagement-selected="console.log($event.detail)">
    <x-ui::chart
        id="student-engagement"
        :config="[
            'type' => 'doughnut',
            'data' => $chartData,
            'options' => ['emitOnClick' => 'engagement-selected'],
        ]"
    />
</div>
```

All standard Chart.js options remain available. Explicit chart and dataset values override the component defaults.

### Navbar

The `navbar` component is a responsive navigation component with a consistent slot structure. Links can be grouped into dropdowns using `<x-ui::navbar.dropdown icon=".." label=".."> ... </x-ui::navbar.dropdown>`.

The desktop navigation is shown from the `xl` breakpoint. Below `xl`, the `navigation` and `right` slots are moved into a hamburger dropdown to avoid crowded desktop menus. The optional `toolbar` slot renders as a fixed bottom toolbar.

```
<x-ui::navbar>
    <!-- Brand Icon -->
    <x-slot:brandIcon>       
       <svg class="w-8 h-8">...</svg>
    </x-slot:brandIcon>

    <!-- Brand Title -->
    <x-slot:brandTitle>       
       <span class="text-lg font-semibold">App Name</span>
    </x-slot:brandTitle>

    <!-- Primary navigation links -->
    <x-slot:navigation>
        <x-ui::navbar.link href="/" icon="home" label="Home" />
        <x-ui::navbar.dropdown label="Company" icon="folder">
            <x-ui::navbar.link href="/about" icon="info" label="About" />
            <x-ui::navbar.link href="/contact" icon="mail" label="Contact" />
        </x-ui::navbar.dropdown>
    </x-slot:navigation>

    <!-- Right section -->
    <x-slot:right>
        <x-ui::navbar.dropdown label="User Menu" icon="user">
            <x-ui::navbar.link href="/profile" icon="cog" label="Profile" />
            <x-ui::navbar.form-link action="/logout" method="post" icon="exit" label="Logout" />
        </x-ui::navbar.dropdown>
    </x-slot:right>

    <!-- Toolbar (fixed bottom bar) -->
    <x-slot:toolbar>
        <x-ui::navbar.link href="/notifications" icon="bell" />
        <x-ui::navbar.link href="/search" icon="search" />
    </x-slot:toolbar>

    <!-- Main content -->
    {{ $slot }}

</x-ui::navbar>
```

Supported navbar slots:
- `brandIcon`
- `brandTitle`
- `navigation`
- `right`
- `toolbar`

### Sidebar
The `sidebar` component is a responsive application shell with a collapsible desktop sidebar, a mobile slide-out menu, and a topbar. Links can be grouped into dropdowns using `<x-ui::sidebar.dropdown icon=".." label=".."> ... </x-ui::sidebar.dropdown>`.

The component prevents horizontal page overflow by constraining the shell, main content, and topbar with responsive `min-w-0` / overflow handling. The `user` slot is displayed at the bottom of the desktop sidebar and inside the mobile slide-out menu. The `toolbar` slot is displayed in the topbar; sidebar links rendered in the topbar become compact `w-auto` actions while normal sidebar/menu links remain full width.

```
<x-ui::sidebar>
    <!-- Optional Brand Icon -->
    <x-slot:brandIcon>       
       <svg>...</svg>
    </x-slot:brandIcon>

    <!-- Optional Brand Title -->
    <x-slot:brandTitle>       
       <span>App Name</span>
    </x-slot:brandTitle>

    <!-- Primary navigation links -->
    <x-slot:navigation>
        <x-ui::sidebar.link href="/" icon="home" label="Home" />
        <x-ui::sidebar.dropdown label="Company" icon="folder">
            <x-ui::sidebar.link href="/about" icon="info" label="About" />
            <x-ui::sidebar.link href="/contact" icon="mail" label="Contact" />
        </x-ui::sidebar.dropdown>
    </x-slot:navigation> 
    
    <!-- User section -->
    <x-slot:user>
        <x-ui::sidebar.dropdown label="User Menu" icon="user">
            <x-ui::sidebar.link href="/profile" icon="cog" label="Profile" />
            <x-ui::sidebar.form-link action="/logout" method="post" icon="exit" label="Logout" />
        </x-ui::sidebar.dropdown>
    </x-slot:user>

    <!-- Toolbar (icon-only links) -->
    <x-slot:toolbar>
        <x-ui::sidebar.link href="/notifications" icon="bell" />
        <x-ui::sidebar.link href="/search" icon="search" />
    </x-slot:toolbar>

    <!-- Main content -->
    {{ $slot }}

</x-ui::sidebar>
```

Supported sidebar slots:
- `brandIcon`
- `brandTitle`
- `navigation`
- `secondary`
- `user`
- `bottom` (used as a mobile fallback when `user` is not supplied)
- `toolbar`

### Dropdown

A dropdown menu can be added to the `navbar` or `sidebar` using `navbar.dropdown` or `sidebar.dropdown`. A `label` property can be provided to name the menu and an optional `icon` property can be used to add an `svg` icon. 


```
<x-ui::navbar.dropdown label="Dropdown" icon="...">
    <x-ui::navbar.link href="..">...</x-ui::navbar.link>
    <x-ui::navbar.link href="..">...</x-ui::navbar.link>
</x-ui::navbar.dropdown>
```

#### Navbar/Sidebar Link

Both `navbar` and `sidebar` contain link components for navigation and actions:

**Navigation Links:**
```
<x-ui::navbar.link icon="..." label="..." href="..."  />
<x-ui::sidebar.link icon="..." label="..." href="..."  />
```

**Form Action Links (for POST/PUT/DELETE operations):**
```
<x-ui::navbar.form-link action="..." method="post" icon="..." label="..."  />
<x-ui::sidebar.form-link action="..." method="post" icon="..." label="..."  />
```

Icons and labels are optional. For regular navigation, use the standard `link` component. For form actions (like logout, delete operations), use the dedicated `form-link` component which includes CSRF protection and method spoofing.

**Component Architecture:**
- `navbar.link` / `sidebar.link` - Navigation links with active state detection
- `navbar.form-link` / `sidebar.form-link` - Form submissions with CSRF protection (POST/PUT/DELETE)

**Sidebar Tooltips:**
Sidebar link components include intelligent tooltip positioning:
- **Sidebar context**: Tooltips appear to the right when collapsed
- **Toolbar context**: Tooltips appear below when collapsed
- **No label**: No tooltip is displayed (prevents artifacts on icon-only links)

### Button and Link

The `button` and `link` components share a consistent set of semantic variants: `primary`, `danger`, `success`, `warning`, `outline-primary`, `outline-danger`, `outline-success`, `outline-warning`, `dark`, `light`, `link`, and `none`.

Buttons use `primary` by default and render with `type="button"`, preventing accidental form submission. Pass another type explicitly when required. Links use the text-style `link` variant by default and can use any button-style variant.

```blade
<x-ui::button>Save</x-ui::button>
<x-ui::button type="submit" variant="success">Submit</x-ui::button>
<x-ui::button variant="danger">Delete</x-ui::button>

<x-ui::link href="/account">Account</x-ui::link>
<x-ui::link href="/reports" variant="outline-primary">View reports</x-ui::link>
```

Both components accept an optional `icon`; provide the visible text through the default slot.

```blade
<x-ui::button variant="light" icon="folder">Information</x-ui::button>
<x-ui::link href="/edit" icon="pencil">Edit</x-ui::link>
```

When an icon is present, the visible text is hidden below the `md` breakpoint while an accessible screen-reader label remains available.

The earlier colour-based variant names (`blue`, `red`, `green`, `yellow`, `oblue`, `ored`, `ogreen`, and `oyellow`) remain supported as backwards-compatible aliases.


### Card

The `card` component acts as a container for content.

```
<x-ui::card>
   // ... card content
</x-ui::card>
```

Cards use a restrained slate border and shadow treatment. The header and footer slots have subtle contrasting backgrounds and dividers so dense pages can be divided into readable sections without introducing strong colour blocks.

Cards can also be configured with optional `header` and `footer` slots

```
<x-ui::card>
   <x-slot:header>
       <h2>Card Title</h2>
   </x-slot:header>

    // card content ...

   <x-slot:footer>
        <div>Footer Area</div>
   </x-slot:footer>
</x-ui::card>
```

### Table

The `table` component includes `thead`, `tbody`, and `tfoot` slots in which head, body, and footer can be defined using the `tr`, `th`, and `td` components. An example table:

```
<x-ui::table>
    <x-slot:thead>
        <x-ui::table.tr>
            <x-ui::table.th>
                Column 1
            </x-ui::table.th>
        </x-ui::table.tr>
    </x-slot:thead>

    <x-slot:tbody>
        <x-ui::table.tr>
            <x-ui::table.td>
                Row 1 Column 1
            </x-ui::table.td>
        </x-ui::table.tr>
    </x-slot:tbody>

    <x-slot:tfoot>
        <x-ui::table.tr>
            <x-ui::table.td>
                Footer Column 1
            </x-ui::table.td>
        </x-ui::table.tr>
    </x-slot:tfoot>
</x-ui::table>
```

Table columns can optionally be configured to only be visible at specified breakpoints (useful when supporting mobile). Ensure matching header and body column have matching attributes.

In this example the column is only visible at `lg` and greater breakpoints
```
<x-ui::table.th showOn="lg">...</x-ui::table.th>

<x-ui::table.td showOn="lg">...</x-ui::table.td>

```
#### Table Sort Link

`name` is required and identifies the column to sort. The slot supplies the label. The optional `paginator` prop derives sort parameter names from the table's paginator. The controller must apply the requested sorting.

```blade
<x-ui::link-sort name="name" default-sort="name">Name</x-ui::link-sort>
```

Without a paginator or explicit overrides, this uses `sort` and `direction`. Set `default-sort` to match the controller's default column; it otherwise defaults to `id`.

#### Paginator

Pass the length-aware paginator returned by Laravel's `paginate()` through `:items`:

```blade
<x-ui::paginator :items="$users" />
```

The component derives the size parameter from `getPageName()`. Default `page` uses `size`. The selected size falls back to the paginator's `perPage()`. The controller must read the size parameter before fetching records; the component does not change the database query.

Use `:options` to override the default sizes (`10`, `25`, `50`, `100`, `500`). Supported `variant` colours are `green`, `red`, `dark`, `purple`, and `light`.

```blade
<x-ui::paginator :items="$users" :options="[10 => 10, 25 => 25, 50 => 50]" variant="green" />
```

#### Multiple Tables With Independent Sorting And Pagination

Give each query a unique Laravel `pageName`, then pass the matching paginator to both components. No component identity prop is needed. A trailing `_page` is removed before deriving the other parameter names; other custom names have `_size`, `_sort`, and `_direction` appended directly. Use distinct prefixes for each table.

| Page name | Size | Sort | Direction |
| --- | --- | --- | --- |
| `page` | `size` | `sort` | `direction` |
| `users_page` | `users_size` | `users_sort` | `users_direction` |
| `projects_page` | `projects_size` | `projects_sort` | `projects_direction` |

Controller example (using `App\Models\User`, `App\Models\Project`, `Illuminate\Http\Request`, and `Illuminate\Validation\Rule`):

```php
public function index(Request $request)
{
    $state = $request->validate([
        'users_size' => ['sometimes', 'integer', Rule::in([10, 25, 50, 100, 500])],
        'users_sort' => ['sometimes', Rule::in(['name', 'email'])],
        'users_direction' => ['sometimes', Rule::in(['asc', 'desc'])],
        'projects_size' => ['sometimes', 'integer', Rule::in([10, 25, 50, 100, 500])],
        'projects_sort' => ['sometimes', Rule::in(['name', 'created_at'])],
        'projects_direction' => ['sometimes', Rule::in(['asc', 'desc'])],
    ]);

    $users = User::query()
        ->orderBy($state['users_sort'] ?? 'name', $state['users_direction'] ?? 'asc')
        ->orderBy('id')
        ->paginate(
            perPage: (int) ($state['users_size'] ?? 10),
            pageName: 'users_page',
        )
        ->withQueryString();

    $projects = Project::query()
        ->orderBy($state['projects_sort'] ?? 'name', $state['projects_direction'] ?? 'asc')
        ->orderBy('id')
        ->paginate(
            perPage: (int) ($state['projects_size'] ?? 10),
            pageName: 'projects_page',
        )
        ->withQueryString();

    return view('dashboard', compact('users', 'projects'));
}
```

In `dashboard.blade.php`:

```blade
<x-ui::table>
    <x-slot:thead>
        <x-ui::table.tr>
            <x-ui::table.th>
                <x-ui::link-sort :paginator="$users" name="name" default-sort="name">Name</x-ui::link-sort>
            </x-ui::table.th>
            <x-ui::table.th>
                <x-ui::link-sort :paginator="$users" name="email" default-sort="name">Email</x-ui::link-sort>
            </x-ui::table.th>
        </x-ui::table.tr>
    </x-slot:thead>
    <x-slot:tbody>
        @foreach ($users as $user)
            <x-ui::table.tr>
                <x-ui::table.td>{{ $user->name }}</x-ui::table.td>
                <x-ui::table.td>{{ $user->email }}</x-ui::table.td>
            </x-ui::table.tr>
        @endforeach
    </x-slot:tbody>
</x-ui::table>
<x-ui::paginator :items="$users" />

<x-ui::table>
    <x-slot:thead>
        <x-ui::table.tr>
            <x-ui::table.th>
                <x-ui::link-sort :paginator="$projects" name="name" default-sort="name">Project</x-ui::link-sort>
            </x-ui::table.th>
            <x-ui::table.th>
                <x-ui::link-sort :paginator="$projects" name="created_at" default-sort="name">Created</x-ui::link-sort>
            </x-ui::table.th>
        </x-ui::table.tr>
    </x-slot:thead>
    <x-slot:tbody>
        @foreach ($projects as $project)
            <x-ui::table.tr>
                <x-ui::table.td>{{ $project->name }}</x-ui::table.td>
                <x-ui::table.td>{{ $project->created_at }}</x-ui::table.td>
            </x-ui::table.tr>
        @endforeach
    </x-slot:tbody>
</x-ui::table>
<x-ui::paginator :items="$projects" />
```

`withQueryString()` preserves the other table's state in pagination links. Sort links also preserve existing query parameters. Changing size resets only the matching paginator to page one; sorting preserves its current page.

For a single paginator, use the default page name and read `size` in the controller:

```php
$users = User::query()->paginate($request->integer('size', 10))->withQueryString();
```

For multiple tables without pagination, give each table explicit sorting parameters:

```blade
<x-ui::link-sort name="name" default-sort="name"
    sort-parameter="users_sort" direction-parameter="users_direction">
    Name
</x-ui::link-sort>
```

Explicit `sort-parameter`, `direction-parameter`, and paginator `size-parameter` overrides take precedence over derived names. The controller must read the matching parameters.

### Form

Form elements include single-purpose `input`, `select`, and `textarea` components. These components render only the field itself (no label or validation error output) and require a minimum of a `name` property. All also accept standard HTML attributes.

To render a field together with an optional label and validation error using a single declaration, use the corresponding group component: `input-group`, `select-group`, `textarea-group`, `date-group`, `datetime-group`, `checkbox-group`, `toggle-group`, `range-group`, or `otp-group`. `label` and `error` components can still be used individually.

> When using a file input `type="file"` you can specify an optional variant to style the input -
> `'light'`, `'oblue'`, `'blue'`, `'gray'`, `'dark'`, `'green'`, `'red'`, `'yellow'`, `'purple'`.

Example usage:

#### Input

Given a model variable `$model` with a text `name` attribute, number `quantity` attribute, `description` textarea attribute and file `photo` attribute, the inputs could be used as follows.

Use `input` when you only need the input element:

``` 
<div>
    <x-ui::form.label for="name">Name</x-ui::form.label>
    <x-ui::form.input name="name" value="..." />
    <x-ui::form.error for="name" />
</div>

<div>
    <x-ui::form.label for="quantity">Quantity</x-ui::form.label>
    <x-ui::form.input type="number" name="quantity" value="..." />
    <x-ui::form.error for="quantity" />
</div>

<div>
    <x-ui::form.label for="photo">Photo</x-ui::form.label>
    <x-ui::form.input type="file" variant="light" name="photo" />
    <x-ui::form.error for="photo" />
</div>
```

Use `input-group` when you want the input with an optional label and validation error:

```
<x-ui::form.input-group label="Name" name="name" value="..." />
<x-ui::form.input-group type="number" label="Quantity" name="quantity" value="..." />
<x-ui::form.input-group type="file" variant="light" label="Photo" name="photo" />
<x-ui::form.textarea-group label="Description" name="description" value="..." />
```

#### Select

Given an options list variable named `$roles` and a model named `$model` with a `role` attribute, the select could be used as follows:

```
<x-ui::form.select-group label="Role" name="role" :options="$roles" value="..." />
```

A custom placeholder property can also be provided which overrides the default.

```
<x-ui::form.select-group label="Role" name="role" :options="$roles" value="..."
                         placeholder="Select a role.." />
```

Extra attributes such as `wire:model` and `required` are applied to the `<select>`. On `select-group`, `class` styles the outer wrapper.

#### Date

A custom date picker component is also available and can be used instead of a standard date input.

Use `date` when you only need the field:

```
<x-ui::form.date name="date" value="{{ now()->format('Y-m-d') }}" class="w-64" />
```

Use `date-group` when you want the field with an optional label and validation error:

```
<x-ui::form.date-group name="date" label="Date" value="{{ now()->format('Y-m-d') }}" class="w-64" />
```
The value may be `null`, empty, or a string in `Y-m-d` format. An empty value stays blank. The picker displays a readable date while its hidden input submits `Y-m-d`; `Clear` empties both. Pass `wire:model` to `date` or `date-group` to bind that ISO value in Livewire.

#### DateTime

A custom datetime picker component is also available and can be used instead of a standard datetime input.

Use `datetime` when you only need the field:

```
<x-ui::form.datetime name="date" label="Date" value="{{ now()->format('Y-m-d H:i:s') }}" class="w-64" />
```

The `value` may be `null` or an empty string. In that case the visible input starts blank and no datetime value is submitted until the user chooses one. The picker also includes a `Clear` action to return the value to blank.

The component renders a hidden input containing the submitted value and a readonly text input for the picker display. The submitted value uses the canonical `Y-m-d H:i:s` format.

Use `datetime-group` when you want the field with an optional label and validation error:

```
<x-ui::form.datetime-group name="date" label="Date" value="{{ now()->format('Y-m-d H:i:s') }}" class="w-64" />
```
> The input value should be `null`, an empty string, or a string in format `Y-m-d H:i:s`.

Existing times retain their exact minute when the picker initializes or receives a new Livewire value. The picker offers every minute of the hour.

#### Checkbox

Use `checkbox` when you need a single native checkbox field with optional label and description content. Extra attributes such as `wire:model`, `disabled`, and `required` are forwarded to the underlying checkbox input. The `class` attribute is applied to the wrapping label.

```
<x-ui::form.checkbox
    name="terms"
    value="accepted"
    label="Accept terms"
    description="I agree to the terms and conditions."
    :checked="old('terms') === 'accepted'"
/>
```

For custom label markup, pass a slot. The input remains wrapped in a label so the whole row stays clickable:

```
<x-ui::form.checkbox name="terms" value="accepted" :checked="$model->accepted_terms">
    <span>
        <span class="block font-medium text-zinc-900 dark:text-zinc-50">Accept terms</span>
        <span class="mt-1 block text-xs text-zinc-500 dark:text-zinc-400">I agree to the terms and conditions.</span>
    </span>
</x-ui::form.checkbox>
```

Use `checkbox-group` when several checkboxes should submit as one array field. The group automatically appends `[]` to the input name when needed, checks matching values from the `value` array, and renders the validation error for the base field name.

```
<x-ui::form.checkbox-group
    name="roles"
    label="Roles"
    description="Choose one or more roles for this user."
    :value="old('roles', $user->roles->pluck('id')->all())"
    :options="[
        1 => 'Administrator',
        2 => 'Editor',
        3 => 'Viewer',
    ]"
/>
```

Options can also include descriptions and disabled states:

```
<x-ui::form.checkbox-group
    name="notifications"
    label="Notifications"
    :value="$enabledNotifications"
    variant="card"
    :options="[
        'email' => ['label' => 'Email', 'description' => 'Send updates by email.'],
        'sms' => ['label' => 'SMS', 'description' => 'Send urgent updates by text.'],
        'post' => ['label' => 'Post', 'description' => 'Postal updates are unavailable.', 'disabled' => true],
    ]"
/>
```

Available `checkbox-group` variants:
- `plain`
- `card`

In Livewire components, pass `wire:model` directly to either component. For a single checkbox, bind a boolean or scalar property:

```
<x-ui::form.checkbox
    name="published"
    label="Published"
    wire:model="published"
/>
```

For a checkbox group, bind an array property. Livewire receives the selected checkbox values from each repeated input:

```
<x-ui::form.checkbox-group
    name="selectedRoles"
    label="Roles"
    wire:model="selectedRoles"
    :options="$roles"
/>
```

#### Toggle

Use `toggle` when you need a native checkbox-based boolean field. This component forwards extra attributes such as `wire:model`, `disabled`, `required`, and `class` to the underlying checkbox input so it works cleanly with standard forms and Livewire.

```
<x-ui::form.toggle name="published" :checked="$model->published" />
```

You may also provide the visible content directly using `label` and `description` props:

```
<x-ui::form.toggle
    name="published"
    label="Published"
    description="Make this record visible to other users."
    :checked="$model->published"
/>
```

For fully custom content, pass a slot. The checkbox remains wrapped in a label so the whole row stays clickable:

```
<x-ui::form.toggle name="published" :checked="$model->published">
    <span>
        <span class="block font-medium text-zinc-900 dark:text-zinc-50">Published</span>
        <span class="mt-1 block text-xs text-zinc-500 dark:text-zinc-400">Make this record visible to other users.</span>
    </span>
</x-ui::form.toggle>
```

Use `toggle-group` when you want the field with an optional label and validation error:

```
<x-ui::form.toggle-group
    name="published"
    label="Published"
    description="Make this record visible to other users."
    :checked="$model->published"
    variant="card"
/>
```

Available `toggle-group` variants:
- `plain`
- `card`

#### Range

Use `range` for a styled slider input with a live value display and tick marks.

Tick marks follow the configured `step` for short ranges and are limited to 11 for larger ranges.

```
<x-ui::form.range name="score" min="1" max="5" step="1" :value="$model->score" />
```

Use `range-group` when you want the field with an optional label and validation error:

```
<x-ui::form.range-group
    name="score"
    label="Score"
    min="1"
    max="5"
    step="1"
    :value="$model->score"
    variant="oblue"
/>
```

Available range variants:
- `light`
- `oblue`
- `blue`
- `gray`
- `dark`
- `green`
- `red`
- `yellow`
- `purple`

#### OTP

Use `otp` to render a one-time-passcode style sequence of single-character inputs. The component stores values as an array using the provided field name.

Extra input attributes such as `required` and `inputmode` are applied to every digit. With `wire:model="code"` (or `wire:model.live="code"`), digits bind to `code.0`, `code.1`, and so on, so the Livewire property should be an array.

```
<x-ui::form.otp name="code" length="6" />
```

Use `otp-group` when you want the field with an optional label and validation error:

```
<x-ui::form.otp-group name="code" label="Verification code" length="6" />
```

#### Confirm

The `confirm` component uses AlpineJS to display a native browser dialog with a message and `Yes` / `No` buttons. It supports `mode="form"` (the default) and `mode="livewire"`. Other mode values are rejected.

In both modes, initial focus goes to `No`. Clicking `No`, pressing Escape, or clicking the backdrop requests cancellation. Dismissal is ignored while an action is busy. Focus returns to the trigger after cancellation or a completed Livewire action that closes the dialog.

**Normal forms**

In form mode, clicking the trigger opens the dialog. Clicking `Yes` closes it, checks browser form validation, and submits the nearest wrapping form through `requestSubmit()`, preserving submit event handlers. A successful native submission keeps the buttons disabled while navigation proceeds. If validation fails, submission is prevented, no submit event occurs, or submission throws, the component remains retryable. For intercepted asynchronous form submissions, the application must manage loading state while its request is pending.

```blade
<form action="{{ route('records.destroy', $record) }}" method="POST">
    @csrf
    @method('DELETE')

    <x-ui::form.confirm mode="form" variant="ored" message="Delete this record?">
        Delete
    </x-ui::form.confirm>
</form>
```

**Livewire components**

Livewire mode calls actions on the containing Livewire component and does not submit a wrapping form. It requires `confirming-property`, `prepare-action`, `confirm-action`, and `cancel-action`. Pass public property and method names, without parentheses or arguments; keep the selected record or other action data in the host component.

```blade
<x-ui::form.confirm
    mode="livewire"
    confirming-property="confirmingDelete"
    prepare-action="prepareDelete"
    confirm-action="deleteRecord"
    cancel-action="cancelDelete"
    variant="ored"
    icon="trash"
    message="Delete this record?"
>
    Delete
</x-ui::form.confirm>
```

For example, the host Livewire component can define:

```php
public bool $confirmingDelete = false;

public function prepareDelete(): void
{
    $this->confirmingDelete = true;
}

public function deleteRecord(): void
{
    $this->record->delete(); // Apply the host application's authorization and validation here.
    $this->confirmingDelete = false;
}

public function cancelDelete(): void
{
    $this->confirmingDelete = false;
}
```

The trigger calls the prepare action; the dialog follows the boolean confirming property. `Yes` calls the confirm action, and cancellation calls the cancel action. The host actions control when the dialog closes by setting the property to `false`. If validation or an error leaves it `true`, the dialog stays open and the buttons unlock for a retry. Buttons stay disabled while each action is pending, preventing duplicate actions and cancellation during that request.

The dialog uses `wire:ignore.self` to preserve its native open state during Livewire updates. The trigger also receives `wire:loading.attr="disabled"` and a `wire:target` containing the three action names. Override the loading target with `target="deleteRecord,refresh"` when needed.

In either mode, `message` overrides the default text (`Are you sure?`) and is escaped when rendered. The optional `icon` appears on the trigger. Use `variant` for the trigger and `confirm-variant` for the `Yes` button; both default to `red`.

```blade
<x-ui::form.confirm variant="ored" confirm-variant="red" icon="trash" message="Delete this record?">
    Delete
</x-ui::form.confirm>
```

### Flash

A flash component is used to display flash messages before a redirect. A controller action would typically flash a message to the session as part of the redirect

```
return redirect()->route("..")->with(<type>, <message>);
```

where `type` is one of `'success'`, `'error'`, `'info'`, `'warning'` or `'status'` and `message` is the message to display.

The `flash` component should be rendered as part of the main layout

```
<x-ui::flash />

<main>
      {{ $slot }}
</main>
```

> The position of the flash message can be specified using an optional `position` parameter with values `top-right` `top-left` `top-center` `bottom-right` `bottom-left` `bottom-center`
> The flash timeout can be specified using an optional `timeout` parameter as an integer number of milliseconds. Invalid values fall back to `6000`.

```
<x-ui::flash position="top-right" timeout="12000" />
```

### Breadcrumb

A breadcrumb can be used to aid navigation. To configure a breadcrumb component we should pass the crumbs as an associative array containing crumb name and a route. The final crumb typically has no associated route as it represents the current page.

```
<x-ui::breadcrumb :crumbs="[
    'Home' => route(..),
    'Crumb1' => route(..),
    'Crumb2'=> route(..),
    'Current' => ''
    ]"
/>
```

### Badge

Badges provide additional contextual information for other user interface (UI) elements on the page. They enable you to easily show statuses, notifications, and short messages in your app. The badge component has a set of variants `'blue'`, `'gray'`, `'light'`, `'red'`, `'green'`, `'yellow'`, `'indigo'`, `'purple'`, `'pink'`.

```
<x-ui::badge variant="pink">
    Badge
</x-ui::badge>
```

The colour names `slate`, `emerald`, `amber`, `rose`, and `sky` are also accepted as aliases for their corresponding gray, green, yellow, red, and blue variants.

### Chip

The `chip` component renders compact metadata or filter labels. It accepts `blue`/`sky`, `green`/`emerald`, and `slate`/`gray` variants, an optional `icon`, an optional `href`, and `default` or `sm` sizes.

```blade
<x-ui::chip variant="emerald" icon="check-circle">Placement ready</x-ui::chip>
<x-ui::chip variant="slate" size="sm" :href="$detailsUrl">View details</x-ui::chip>
```

### Header

A simple component to use as a page header. Can be combined with `title` component below. For example:

```
<x-ui::header>
        <x-ui::title>Create</x-ui::title>
</x-ui::header>
```

> **Note** header content is flex row, justify between

#### Title 

The `title` component can be used to provide consistent page title, with configurable sizes `xl` (default), `lg`, `md`, `sm`.

```
<x-ui::title size="lg">
    About Us
</x-ui::title>
```

> A `title` is often placed in a `header` to provide a consistent page header 

```
<x-ui::header>
    <x-ui::title size="lg">
       Users
    </x-ui::title>

    <x-ui::link href="#">Create</x-ui::link>
</x-ui::header>
```

### Display

Used to display a `label` and a `value` - typically used in a show view. For example given a `$model` with a name attribute we can display it as follows:

```
<x-ui::display label="Name" value="{{$model->name}}" />
```

Where a more complex value is to be displayed then use the $slot as follows:

```
<x-ui::display label="Name">
    <span>{{$model->name}}"</span>
    <x-ui::badge variant="pink">Pro</x-ui::badge>
</x-ui::display>
```

Display rows support four density/layout variants:

- `default` — a standard responsive label/value row.
- `compact` — reduced vertical spacing for side panels and summaries.
- `tile` — a bordered summary metric suitable for responsive grids.
- `stacked` — a label above its value without row dividers.

The optional `icon` prop places an icon beside the label, and `labelWidth` accepts `sm`, `md`, `lg`, or `xl`.

```blade
<x-ui::display variant="tile" label="Open Opportunities" :value="$count" />
<x-ui::display variant="compact" label="Employer" :value="$employer->name" icon="building-office" />
```

### Section Header

Use `section-header` inside a card header to provide a consistent title, supporting description, optional status badge, and optional actions.

```blade
<x-ui::section-header
    title="Application Details"
    description="Information supplied by the student."
    status="Complete"
    status-variant="emerald"
>
    <x-slot:actions>
        <x-ui::link :href="$editUrl">Edit</x-ui::link>
    </x-slot:actions>
</x-ui::section-header>
```

### Resource Row

The `resource-row` component displays a named resource or checklist item with an icon, optional description, status badge, and action slot. Group rows inside a divided bordered container.

```blade
<div class="divide-y divide-slate-100 overflow-hidden rounded-lg border border-slate-200">
    <x-ui::resource-row title="Offer Evidence" status="Available" status-variant="emerald" icon="document">
        <x-slot:actions>
            <x-ui::link :href="$documentUrl" icon="eye" label="View" />
        </x-slot:actions>
    </x-ui::resource-row>
</div>
```

### Hero
The `hero` component is used for displaying a large box or image with a title and description.

```
<x-ui::hero heading="Hero Heading" subheading="Optional sub-heading">
  Content to display in hero body
</x-ui::hero>
```

### Statistic
The `statistic` component is used to show numbers and data in a block.

```
<x-ui::statistic title="Total Views" 
                 description="The total number of views" 
                 value="1000" 
                 variant="dark"/>
```

The value may also be supplied through the default slot when `value` is `null`. An optional `icon` prop displays an icon beside the value.

Available statistic variants:
- `blue`
- `red` / `rose`
- `green` / `emerald`
- `yellow`
- `pink`
- `sky`
- `indigo`
- `gray`
- `light` / `slate`
- `dark` / `neutral`

### Tabs

The `tabs` and `tab` components work together to provide tabbed panels and work together as follows

```
<x-ui::tabs active="Tab1">
    <x-ui::tabs.tab name="Tab1">
        Tab one content
    </x-ui::tabs.tab>
    <x-ui::tabs.tab name="Tab2">
        Tab two content
    </x-ui::tabs.tab>
    <x-ui::tabs.tab name="Tab3">
        Tab three content
    </x-ui::tabs.tab>
</x-ui::tabs>
```

Tab headings wrap on narrow screens rather than forcing the full page to scroll horizontally. The active heading uses the same subtle blue accent as route-based navigation tabs.

### Navigation Tabs

Use `nav-tabs` for route-based navigation that should visually match interactive tabs. Links wrap responsively and support `primary` and more compact `secondary` variants.

```blade
<x-ui::nav-tabs label="Student sections">
    <x-ui::nav-tabs.link :href="$detailsUrl" :active="request()->routeIs('students.show')">
        Details
    </x-ui::nav-tabs.link>
    <x-ui::nav-tabs.link :href="$placementsUrl" :active="request()->routeIs('students.placements')">
        Placements
    </x-ui::nav-tabs.link>
</x-ui::nav-tabs>

<x-ui::nav-tabs variant="secondary" label="Placement sections">
    <x-ui::nav-tabs.link variant="secondary" :href="$planUrl" :active="$activeSection === 'plan'">
        Placement Plan
    </x-ui::nav-tabs.link>
</x-ui::nav-tabs>
```

Pass `disabled` to render a non-interactive tab with `aria-disabled="true"`.

### Accordion

The `accordion` and `accordion.item` components work together to provide collapsible panels. Only one item is open at a time.

```
<x-ui::accordion>
    <x-ui::accordion.item name="courses" title="Courses" summary="3 selected">
        Course content
    </x-ui::accordion.item>

    <x-ui::accordion.item name="modules" title="Modules" summary="2 selected">
        Module content
    </x-ui::accordion.item>
</x-ui::accordion>
```

The accordion can open a panel by default by passing the item name to the `open` prop:

```
<x-ui::accordion open="courses">
    <x-ui::accordion.item name="courses" title="Courses">
        Course content
    </x-ui::accordion.item>
</x-ui::accordion>
```

The `accordion.item` component accepts:
- `name`: unique item identifier, used to track open state
- `title`: title bar text
- `summary`: optional right-aligned summary text
- `variant`: title bar colour when open, one of `sky`, `slate`, `blue`, `green`, `yellow`, `red`

```
<x-ui::accordion.item
    name="students"
    title="Students"
    summary="12 selected"
    variant="blue"
>
    Student content
</x-ui::accordion.item>
```

### Svg

The `svg` component renders icons from a Blade icon library folder.

Required / common props:
- `icon`: the icon name to render
- `set`: icon set folder name (defaults to `icons`)
- `size`: `sm`, `md`, `lg`, `xl` (defaults to `sm`)

Optional SVG props:
- `viewBox` (default `0 0 24 24`)
- `fill` (default `none`)
- `stroke` (default `currentColor`)
- `strokeWidth` (default `1.5`)

Additional HTML / SVG attributes (for example `class`) can also be passed through.

Default `icons` set currently includes:
`academic-cap`, `add`, `add-user`, `adjustments-horizontal`, `archive-box`, `arrow-down`, `arrow-down-tray`, `arrow-left`, `arrow-path`, `arrow-right`, `arrow-up`, `arrow-up-tray`, `arrow-uturn-down`, `avatar`, `backspace`, `badge`, `bars`, `bars-arrow-down`, `bars-down`, `bars-arrow-up`, `bars-up`, `bell`, `book-open`, `calendar-days`, `camera`, `chart`, `chat-bubble-left`, `check`, `check-circle`, `chevron-down`, `chevron-left`, `chevron-right`, `chevron-up`, `chevron-up-down`, `cog`, `cog-6-tooth`, `computer-desktop`, `document`, `document-duplicate`, `edit`, `envelope`, `exclamation-triangle`, `exit`, `eye`, `finger-print`, `folder`, `funnel`, `globe`, `home`, `identification`, `inbox-arrow-down`, `info`, `light-bulb`, `link`, `list`, `list-bullet`, `magnifying-glass`, `mail`, `minus`, `moon`, `paperclip`, `phone`, `photo`, `pie`, `plus`, `search`, `tag`, `trash`, `user`, `users`, `wrench`, `x-mark`

```
<x-ui::svg icon="trash" size="sm" />
<x-ui::svg icon="check-circle" size="md" class="text-green-600" />
```

### Modal

The `modal` component uses a native browser dialog, which handles focus containment and restores focus when closed. Use `autofocus` on a content element to choose its initial focus; the legacy `focusable` attribute is no longer needed. The modal accepts a `name` prop which must be unique on the page containing the modal. It also can be configured with optional `title` and `footer` slots.
   
The modal accepts a `dismissable` prop that defaults to `false`, preventing Escape and backdrop dismissal. Set `:dismissable="true"` to allow both. The close button and named close events remain available in either mode.

```
<x-ui::modal name="test" :dismissable="true">
    <x-slot:title>
        ...
    </x-slot:title>

    <!-- modal content -->

    <x-slot:footer>
        ...
    </x-slot:footer>
</x-ui::modal>  
```

#### Modal Trigger Component

Use `x-ui::modal.trigger` to trigger modal open/close events without wiring `@click` dispatches manually.

Default behaviour:
- Uses `x-ui::button` as the trigger component
- Dispatches `open-modal` by default

Supported props:
- `for` (required): the modal name to target
- `action` (optional): `open` or `close` (default: `open`)
- `component` (optional): dynamic component name (default: `ui::button`)

##### Open (default)
```
<x-ui::modal.trigger for="test">
    Open
</x-ui::modal.trigger>
```

##### Close
```
<x-ui::modal.trigger for="test" action="close" variant="light">
    Close
</x-ui::modal.trigger>
```

If the modal contains a form and this close trigger is rendered inside that form, set `type="button"` on the trigger. The default trigger component (`x-ui::button`) would otherwise submit the form.

```
<x-ui::modal.trigger for="test" action="close" variant="light" type="button">
    Close
</x-ui::modal.trigger>
```

##### Use a Different Trigger Component
```
<x-ui::modal.trigger for="test" component="ui::link" variant="link">
    Open as Link
</x-ui::modal.trigger>
```

#### Trigger

As an alternative to `modal.trigger`, you can still trigger a modal manually using a `button` and `@click` dispatch (named `test` in this example).

##### Open
```
<x-ui::button variant="dark" @click="$dispatch('open-modal', 'test')">Open</x-ui::button>
```
##### Close
```
<x-ui::button variant="light" @click="$dispatch('close-modal', 'test')">Close</x-ui::button>
```

### Steps

The `steps` component can be used to display a list of steps with their completion status

```
<x-ui::steps :steps="[1 => ['Details', true], 2 => ['Employment', true], 3 => ['Plan', true], 4 => ['Safety', false]]" />
```

The component also accepts optional parameters

`numbered` - displays step number
`percentage` - displays a percentage completion bar beneath the steps
`variant` - colour of completed steps `green` `blue` `indigo` (default) 


### Chart

The `chart` component wraps [Chart.js](https://www.chartjs.org/) and is intended to replace the older `highchart` component.

It accepts:
- an `id` prop for the canvas element
- a `config` prop containing a Chart.js configuration array/object
- or a slot containing a JavaScript chart config object

The component loads pinned Chart.js 4.5.1 from CDN and applies light/dark theme defaults for legend, title, tooltip, axes, and chart background. It also supports the serializable tooltip maps, doughnut centre text, click events, accessibility props, pie/doughnut presentation defaults, and reduced-motion behaviour documented in the earlier Chart section.

Using the `config` prop:

```blade
<x-ui::chart
    id="sales-chart"
    :config="[
        'type' => 'bar',
        'data' => [
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr'],
            'datasets' => [
                [
                    'label' => 'Sales',
                    'data' => [12, 19, 8, 15],
                    'backgroundColor' => '#2563eb',
                ],
            ],
        ],
        'options' => [
            'plugins' => [
                'title' => [
                    'display' => true,
                    'text' => 'Monthly Sales',
                ],
            ],
        ],
    ]"
/>
```

Using the slot:

```blade
<x-ui::chart id="traffic-chart">
{
    type: 'line',
    data: {
        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'],
        datasets: [{
            label: 'Visits',
            data: [120, 160, 140, 180, 210],
            borderColor: '#16a34a',
            backgroundColor: 'rgba(22, 163, 74, 0.2)'
        }]
    }
}
</x-ui::chart>
```

### Highchart

The `highchart` component remains available for backward compatibility, but it is now deprecated and should be replaced with `x-ui::chart` for new work and future migrations.

### Rating

The rating component requires a `value` parameter. The `max` parameter is optional and defaults to `5`. The `size` parameter can be `sm` `md` `lg` and defaults to `md`.  

```
<x-ui::rating value="3.5" size="md" max="5"  />
```

## Props Reference

This section lists the public props currently declared by the Blade components. Standard HTML attributes and classes can also be passed unless noted by the component.

### Navigation

`navbar`
- Slots: `brandIcon`, `brandTitle`, `navigation`, `right`, `toolbar`
- Uses `dark` and `mobileOpen` Alpine state.
- Desktop navigation is shown from `xl`; below `xl`, `navigation` and `right` render in the hamburger dropdown.

`navbar.link`
- `href` default `#`
- `label` required by the component declaration, but may be omitted in practice for icon-only usage
- `icon` required by the component declaration
- `active` default `request()->url() === url($href ?? '#')`

`navbar.dropdown`
- `icon` default `folder`
- `label` default empty string

`navbar.form-link`
- `action` default `#`
- `label` default `null`
- `icon` required by the component declaration
- `method` default `post`

`sidebar`
- Slots: `brandIcon`, `brandTitle`, `navigation`, `secondary`, `user`, `bottom`, `toolbar`
- Uses `dark`, `mobileOpen`, and `collapsed` Alpine state.
- `user` renders at the desktop sidebar bottom and in the mobile slide-out menu.
- `bottom` is used as a mobile fallback when no `user` slot is supplied.
- `toolbar` renders in the topbar.

`sidebar.link`
- `href` default `#`
- `label` default `null`
- `icon` required by the component declaration
- `collapsed` default `false`
- `active` default `request()->url() === url($href ?? '#')`
- Links are full width in sidebar/menu contexts and compact in the topbar.

`sidebar.dropdown`
- `icon` default `folder`
- `label` default empty string
- `collapsed` default `false`

`sidebar.form-link`
- `action` default `#`
- `label` default `null`
- `icon` required by the component declaration
- `method` default `post`
- `collapsed` default `false`

### Basic UI

`heading`
- `level` default `1`; accepted values are `1`, `2`, `3`, `4`, `5`, `6`

`divider`
- `type` default `top`; accepted values are `top`, `bottom`

`button`
- `variant` default `primary`
- `icon` default `null`
- `type` defaults to `button`; standard button attributes may override it
- Variants: `primary`, `danger`, `success`, `warning`, `outline-primary`, `outline-danger`, `outline-success`, `outline-warning`, `dark`, `light`, `link`, `none`
- Legacy aliases remain supported: `blue`, `red`, `green`, `yellow`, `oblue`, `ored`, `ogreen`, `oyellow`

`link`
- `variant` default `link`
- `href` default `#`
- `icon` default `null`
- Variants: `primary`, `danger`, `success`, `warning`, `outline-primary`, `outline-danger`, `outline-success`, `outline-warning`, `dark`, `light`, `link`, `none`
- Legacy aliases remain supported: `blue`, `red`, `green`, `yellow`, `oblue`, `ored`, `ogreen`, `oyellow`

`avatar`
- `size` default `xs`; accepted values are `xs`, `sm`, `md`, `lg`

`badge`
- `variant` default `blue`; accepted values are `blue`, `gray`, `light`, `red`, `green`, `yellow`, `indigo`, `purple`, `pink`

`breadcrumb`
- `crumbs` default `[]`; pass an associative array of label => URL

`card`
- Slots: default, `header`, `footer`

`display`
- `label` required
- `value` default `null`
- `icon` default `null`
- `labelWidth` default `md`; accepted values are `sm`, `md`, `lg`, `xl`

`header`
- No component-specific props

`hero`
- `heading` required
- `subheading` default `null`

`rating`
- `value` default `0`
- `max` default `5`
- `size` default `md`; accepted values are `sm`, `md`, `lg`

`statistic`
- `title` required
- `value` required by the component declaration, but the slot is used when `value` is null
- `variant` default `dark`; accepted values are `blue`, `red`, `rose`, `green`, `emerald`, `yellow`, `pink`, `sky`, `indigo`, `gray`, `light`, `slate`, `dark`, `neutral`
- `description` default `null`
- `icon` default `null`

`title`
- `size` default `xl`; accepted values are `xl`, `lg`, `md`, `sm`

### Tables And Pagination

`table`
- Slots: `thead`, `tbody`, `tfoot`

`table.tr`
- `hover` default `false`

`table.th`
- `showOn` default `null`; accepted values are `sm`, `md`, `lg`, `xl`, `2xl`
- `scope` default `col`

`table.td`
- `showOn` default `null`; accepted values are `sm`, `md`, `lg`, `xl`, `2xl`

`link-sort`
- `name` required; the column to sort
- `paginator` optional; derives sort and direction parameter names from `getPageName()`
- `defaultSort` default `id`; match the controller's default sort column
- `sortParameter` and `directionParameter` default `null`; explicit overrides
- Without a paginator or overrides, uses `sort` and `direction`

`paginator`
- `items` required; use an `Illuminate\Pagination\LengthAwarePaginator` (needs `lastPage()`)
- `size` default `10`; selected size uses the derived request parameter, falling back to `perPage()`
- `sizeParameter` default `null`; derives from `getPageName()`, or accepts an explicit override
- Page links and size changes use the supplied paginator's page name
- `options` default `['10' => 10, '25' => 25, '50' => 50, '100' => 100, '500' => 500]`
- `variant` default `default`; supported colour variants include `green`, `red`, `dark`, `purple`, `light`, with default blue styling otherwise

### Forms

`form.input`
- `name` required
- `value` default `null`
- `variant` default `light`; only affects `type="file"` styling
- `type` default `text`

`form.input-group`
- `name` required
- `label` default `null`
- `value` default `null`
- `variant` default `light`
- `type` default `text`
- `icon` default `null`

`form.select`
- `name` required
- `value` default `null`
- `options` default `[]`
- `placeholder` default `Choose option...`

`form.select-group`
- `name` required
- `icon` default `null`
- `label` default `null`
- `value` default `null`
- `options` default `[]`
- `placeholder` default `Choose option...`

`form.textarea`
- `name` required
- `value` default `null`

`form.textarea-group`
- `name` required
- `value` default `null`
- `icon` default `null`
- `label` default `null`

`form.date`
- `name` required
- `value` default empty string; accepts `null`, empty string, or `Y-m-d`

`form.date-group`
- `name` required
- `label` default `null`
- `value` default `null`
- `icon` default `null`

`form.datetime`
- `name` required
- `label` default `null`
- `value` default empty string; accepted values are `null`, empty string, or `Y-m-d H:i:s`

`form.datetime-group`
- `name` required
- `label` default `null`
- `value` default `null`; accepted values are `null`, empty string, or `Y-m-d H:i:s`
- `icon` default `null`

`form.checkbox`
- `name` required
- `value` default `1`
- `label` default `null`
- `description` default `null`
- `checked` default `false`
- `id` default `null`

`form.checkbox-group`
- `name` required
- `label` default `null`
- `description` default `null`
- `value` default `[]`
- `options` default `[]`
- `variant` default `plain`; accepted values are `plain`, `card`
- `icon` default `null`

`form.toggle`
- `name` required
- `label` default `null`
- `description` default `null`
- `checked` default `false`
- `value` default `1`
- `uncheckedValue` default `0`

`form.toggle-group`
- `name` required
- `label` default `null`
- `description` default `null`
- `checked` default `false`
- `variant` default `plain`; accepted values are `plain`, `card`

`form.range`
- `name` required
- `min` default `0`
- `max` default `100`
- `step` default `1`
- `value` default `0`
- `variant` default `light`; accepted values are `light`, `oblue`, `blue`, `gray`, `dark`, `green`, `red`, `yellow`, `purple`

`form.range-group`
- `name` required
- `min` default `0`
- `max` default `100`
- `step` default `1`
- `value` default `0`
- `variant` default `light`
- `label` default `null`
- `icon` default `null`

`form.otp`
- `name` required
- `length` default `6`

`form.otp-group`
- `name` required
- `label` default `null`
- `length` default `6`
- `icon` default `null`

`form.label`
- `icon` default `null`

`form.error`
- `for` required by the component declaration; when supplied, renders the validation error for that field

`form.confirm`
- `mode` default `form`; accepted values are `form` and `livewire`
- `confirmingProperty` default `null`; pass as `confirming-property` (required in Livewire mode)
- `prepareAction`, `confirmAction`, `cancelAction` default `null`; pass as `prepare-action`, `confirm-action`, `cancel-action` (all required in Livewire mode)
- `target` default `null`; in Livewire mode, defaults to the comma-separated action names for `wire:target`
- `message` default `Are you sure?`
- `variant` default `red` (trigger button)
- `confirmVariant` default `red`; pass as `confirm-variant` (confirmation button)
- `icon` default `null`
- Form mode submits the nearest wrapping form; Livewire mode calls host component actions and does not require a form.

### Disclosure And Overlays

`accordion`
- `open` default `null`

`accordion.item`
- `name` required
- `title` required
- `summary` default `null`
- `variant` default `slate`; accepted values are `blue`, `green`, `red`, `sky`, `slate`, `yellow`

`tabs`
- `active` required
- Stores the active tab in `localStorage` and synchronizes the `tab` query parameter

`tabs.tab`
- `name` required

`modal`
- `name` required
- `dismissable` default `false`
- `show` default `false`
- `maxWidth` default `2xl`; accepted values are `sm`, `md`, `lg`, `xl`, `2xl`
- Slots: default, `title`, `footer`
- Escape and backdrop dismissal require `:dismissable="true"`; the close button and named close events work in either mode.

`modal.trigger`
- `for` required
- `action` default `open`; use `open` or `close`
- `component` default `ui::button`

`flash`
- `position` default `top-right`; accepted values are `top-right`, `top-left`, `top-center`, `bottom-right`, `bottom-left`, `bottom-center`
- `timeout` default `6000`
- Reads session keys `success`, `info`, `error`, `warning`, and `status`

`steps`
- `steps` default `[]`; each item should be `[label, complete]`
- `numbered` default `false`
- `percentage` default `false`
- `variant` default `null`; accepted colour variants are `green`, `blue`, or default indigo styling

### Charts And Icons

`chart`
- `id` required
- `config` required by the component declaration, but the slot may be used for a JavaScript config object
- `ariaLabel` default `null`; falls back to a headline generated from `id`
- `fallbackText` default `null`; falls back to the accessible label
- Loads pinned Chart.js 4.5.1 from CDN and redraws on dark mode class changes
- Disables animation when `prefers-reduced-motion: reduce` is active
- Supports `labelMap`, `datasetLabelMap`, `valueMap`, `secondaryValueMap`, and `displayValue` under `options.plugins.tooltip`
- Supports `options.plugins.centreText` and `options.emitOnClick`

`highchart`
- `id` required
- `config` required by the component declaration, but the slot may be used for a Highcharts config object
- Deprecated in favour of `chart`

`svg`
- `icon` default `null`
- `variant` default `null`; retained as a backward-compatible alias for `icon`
- `set` default `icons`
- `size` default `sm`; accepted values are `sm`, `md`, `lg`, `xl`
- `viewBox` default `0 0 24 24`
- `fill` default `none`
- `stroke` default `currentColor`
- `strokeWidth` default `1.5`
- Includes the `building-office` icon for employer and organisation contexts

`icon`
- `icon` required
- `variant` default empty string; accepted values are `mini`, `micro`, or default standard size
