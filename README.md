# Laravel View Components

> **Version 1.3.20**

A simple set of anonymous Laravel Blade View Components to help construct basic user interfaces. Components include:

    1. NavBar with nav item and drop down menu items
    2. Table
    3. Form components
    3. Common Svg Icons
    4. General components including: badge, breadcrumb, button, card, display, flash, page title, modal.

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

To add the components directly into your applications resources folder they can be published using

```
 php artisan vendor:publish --tag=termon/ui
```

## Prerequisite

These components require installation of [Tailwind CSS](https://tailwindcss.com) for styling and [AlpineJS](https:://alpinejs.dev) for interactivity.

## Using Components

The component prefix is `x-ui` followed by the name of the component (separated by :: or . when installed locally )

```
<x-ui::<component-name> // using vendor package
<x-ui.<component-name>  // when published locally (recommended)
```

## Available Components

### Nav

The `nav` component is a nav-bar that contains links with a nav variant `x-ui.link variant="nav"`. These accept standard anchor tag `href` property and the `active` property should be the name of the route so that current route can be highlighted.

```
<x-ui::nav>
    <x-ui::link variant="nav" active="home" href="..">
        Home
    </x-ui::link>
    <x-ui::link variant="nav" active="about" href="..">
        About
    </x-ui::link>
    <x-ui::link variant="nav" active="contact" href="..">
        Contact
    </x-ui::link>
</x-ui::nav>
```

#### Dropdown

A dropdown menu can be added to the navbar using the `nav.drop` component that requires a property `title` used to name the menu. This component then contains one or more standard nav links - `link` components with `variant='nav'`.

```
<x-ui::nav>
    ...
    <x-ui::nav.drop title="Dropdown">
        <x-ui::link variant="nav" active="about" href="{{route('about')}}">About</x-ui::link>
        <x-ui::link variant="nav" active="home" href="{{route('home')}}">Home</x-ui::link>
        <x-ui::link variant="nav" active="books.index" href="{{route('books.index')}}">Books</x-ui::link>
    </x-ui::nav.drop>
</x-ui::nav>
```

### Button and Link

The `button` and `link` components can be configured with several variants (`'blue'`, `'red'`, `'green'`, `'yellow'`, `'dark'`, `'light'`, `'oblue'`, `'ored'`, `'link'` or `'nav'`)

Following examples provide a `'light'` variant button, and standard `link`. Adding a `variant` property to the link allows it to be styled as one of the buttons.

```
<x-ui::button variant="light">Light</button>
<x-ui::link>Link</button>
<x-ui::link variant='oblue'>Outline Blue Link</button>
```

### Card

The `card` component acts as a container for content.

```
<x-ui::card>
   // ... card content
</x-ui::card>
```

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

The `table` component includes `thead` and `tbody` slots in which head and body can be defined using the `tr` and `th` components. An example table:

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
    <x-slot:tbody>
</x-ui::table>
```

### Form

Form elements include: `input`, `select`, and `textarea` components. All require a minimum of a `name` property and an optional `label` property. All, also accept any standard html properties. Additionally `label` and `error` components can be used individually.

> When using a file input `type="file"` you can specify an optional variant to style the input -
> `'light'`, `'blue'`, `'gray'`, `'dark'`, `'green'`, `'red'`, `'yellow'`, `'purple'`.

Example usage:

#### Input

Given a model variable `$model` with a text `name` attribute, number `quantity` attribute, `description` textarea attribute and file `photo` attribute , the inputs could be used as follows:

```
<x-ui::form.input label="Name" name="name" value="..." />
<x-ui::form.input type="number" label="Quantity" name="quantity" value="..." />
<x-ui::form.input type="file" variant="light" label="Photo" name="photo" value="..." />
<x-ui::form.textarea label="Description" name="description" value="..." />
```

#### Select

Given an options list variable named `$roles` and a model named `$model` with a `role` attribute, the select could be used as follows:

```
<x-ui::form.select label="Role" name="role" :options="$roles" value="..." />
```

### Flash

A flash component is used to display flash messages before a redirect. A controller action would typically flash a message to the session as part of the redirect

```
return redirect()->route("..")->with(<type>, <message>);
```

where `type` is one of `'success'`, `'error'`, `'info'` or `'warning'` and `message` is the message to display.

The `flash` component should be rendered as part of the main layout

```
<x-ui::flash />

<main>
      {{ $slot }}
</main>
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

### Header

A simple component to use as a page header. Can be combined with `title` component below. For example:

```
<x-ui::header>
        <x-ui::title>Create</x-ui::title>
</x-ui::header>
```

> **Note** header content is flex row, justify between

### Display

Used to display a `label` (optional) and a `value` - typically used in a show view. For example given a `$model` with a name attribute we can display it as follows:

```
<x-ui::display label="Name" value="{{$model->name}}" />
<x-ui::display value="{{$model->name}}" />
```

Where a more complex value is to be displayed then use the $slot as follows:

```
<x-ui::display label="Name">
    <span>{{$model->name}}"</span>
    <x-ui::badge variant="pink">Pro</x-ui::badge>
</x-ui::display>
```

### Title

A simple component to provide consistent page title. It a set of sizes `xl` (default), `lg`, `md`.

```
<x-ui::title size="lg">
    About Us
</x-ui::title>
```

### Tabs

The `tabs` and `tab` components work together to provide tabbed panels and work together as follows

```
<x-ui.tabs active="Tab1">
    <x-ui.tabs.tab name="Tab1">
        Tab one content
    </x-ui.tabs.tab>
    <x-ui.tabs.tab name="Tab2">
        Tab two content
    </x-ui.tabs.tab>
    <x-ui.tabs.tab name="Tab3">
        Tab three content
    </x-ui.tabs.tab>
</x-ui.tabs.tab>
```

### Svg

Svg component accepts a `variant` attribute, containing the name of the svg e.g. (`add` `add-user`, `arrow-left`, `arrow-right` `arrow-down` `arrow-up` `badge` `bars` `bars-up` `bars-down` `chevron` `edit` `eye` `globe` `home` `info` `logo` `minus` `pie` `plus` `tag` `trash`)

It also accepts a size attribute with values `sm` `md` `lg` and `xl`

```
<x-ui.svg variant="trash" size='sm' />
```
