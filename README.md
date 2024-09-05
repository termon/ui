# Laravel View Components

A simple set of anonymous Laravel Blade View Components to help construct basic user interfaces. Components include:

    1. NavBar with nav item and drop down menu items
    2. Table
    3. Form components
    3. Common Svg Icons
    4. General components including: badge, breadcrumb, button, card, display, flash, page title, modal.

## Installation

Use composer to install the library.
```
$ composer require termon/ui
```

**Note**: if the package is not available on `packagist` then add the `github` repository to your composer.json file

```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/termon/ui"
    }
],
```

## Prerequisite
These components require installation of [Tailwind CSS](https://tailwindcss.com) for styling and [AlpineJS](https:://alpinejs.dev) for interactivity.

## Using Components

The component prefix is ```x-ui``` followed by the name of the component (separated by :: )

```
<x-ui::<component-name>
```

## Available Components

### Nav 
The `nav` component is a nav-bar that contains nav items `nav.item`. These accept standard anchor tag ```href``` property and the ```active``` property should be the name of the route so that current route can be highlighted.

```
<x-ui::nav>
    <x-ui::nav.item active="home" href="..">
        Home
    </x-ui::nav.item>
    <x-ui::nav.item active="about" href="..">
        About
    </x-ui::nav.item>
    <x-ui::nav.item active="contact" href="..">
        Contact
    </x-ui::nav.item>
</x-ui::nav>
```

#### Dropdown
A dropdown menu can be added to the navbar using the ```nav.drop``` component that requires a property ```title``` used to name the menu. This component then contains one or more ```nav.drop.link``` components which act in a similar fashion to the ```nav.item``` components

```
<x-ui::nav>
    ...
    <x-ui.nav.drop title="Dropdown">
        <x-ui.nav.drop.link active="about" href="{{route('about')}}">About</x-ui.nav.drop.link>  
        <x-ui.nav.drop.link active="home" href="{{route('home')}}">Home</x-ui.nav.drop.link> 
        <x-ui.nav.drop.link active="books.index" href="{{route('books.index')}}">Books</x-ui.nav.drop.link>  
    </x-ui.nav.drop>
</x-ui::nav>
```

### Button and Link
The ```button``` and ```link``` components can be configured with several variants (`'blue'`, `'red'`, `'green'`, `'yellow'`, `'dark'`, `'light'`, `'oblue'`, `'ored'` and `'link'`)  

Following examples provide a `'light'` variant button, and standard `link`. Adding a `variant` property to the link allows it to be styled as one of the buttons.

 ```
<x-ui::button variant="light">Light</button>
<x-ui::link>Link</button>
<x-ui::link variant='oblue'>Outline Blue Link</button>
 ```


### Card
The ```Card``` component acts as a container for content. 

```
<x-ui::card>
   // ... card content
</x-ui::card>
```

Cards can also be configured with optional ```header``` and ```footer``` slots

```
<x-ui::card>
   <x-slot:header>Card Title</x-slot:header>

    // card content ...

   <x-slot:footer>Footer area</x-slot:footer>
</x-ui::card>
```

### Table
The ```Table``` component includes a `head` slot in which column headers can be defined using the `col` component. Table rows are output using `row` and `cell` components. An example table:

```
<x-ui::table>
    <x-slot:head>
        <x-ui::table.col>
            Column 1
        </x-ui::table.col>           
    </x-slot:head>

    <x-ui::table.row>
        <x-ui::table.cell>
            Row column
        </x-ui::table.cell>
    </x-ui::table.row>
</x-ui::table>
```

### Form

Form elements include: `input`, `input-file`, `select`, and `textarea` components. All require a minimum of a `name` property and an optional `label` property. All, also accept any standard html properties. 

Example usage:

#### Input
Given a model variable `$model` with a `name` attribute, the input could be used as follows: 
```
<x-ui::form.input label="Name" name="name" value="..." />
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
Badges provide additional contextual information for other user interface (UI) elements on the page. They enable you to easily show statuses, notifications, and short messages in your app. The badge component has a set of variants ('blue', 'gray', 'red', 'green', 'yellow', 'indigo', 'purple', 'pink')

```
<x-ui::badge variant="pink">
    Badge
</x-ui::badge>
```

### Header
A simple component to use as a page (title) header. Can be combined with `title` component below. For example:

```
<x-ui::header>
        <x-ui::title>Create</x-ui::title>
</x-ui::header>
```

### Title
A simple component to provide consistent page title 

```
<x-ui::title>
    About Us
</x-ui::title>
```