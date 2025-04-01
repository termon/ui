# Laravel View Components

> **Version 1.4.3**

A simple set of anonymous Laravel Blade View Components (styled using TailwindCSS 4), to help construct basic user interfaces. Components include:

    1. Navbar/Sidebar with nav items and drop down menu items
    2. Table
    3. Form components
    3. Common Svg Icons
    4. General components including: 
        - badge, 
        - breadcrumb, 
        - button, 
        - card, 
        - display, 
        - flash, 
        - header,
        - hero,
        - modal
        - steps 
        - tabs, 

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

### Tailwind 4 Configuration 
Add following line to your `app.css` file

`@source '../../vendor/termon/ui/src/resources/views/components/**/*.blade.php';`

## Using Components

The component prefix is `x-ui` followed by the name of the component (separated by :: or . when installed locally )

```
<x-ui::<component-name> // using vendor package
<x-ui.<component-name>  // when published locally (recommended)
```

## Available Components

### Navbar

The `navbar` component is a navbar that contains `links` using `x-ui::navbar.link` elements. These accept standard anchor tag properties and an `active` property containing the name of the route this link navigates to, so that the current route can be highlighted.

```
<x-ui::navbar>

    <!-- Optional Navbar Title -->
    <x-slot:title>
       ...
    </x-slot:title>

    <!-- Navbar links -->
    <x-ui::navbar.link active="home" href=".."> ... </x-ui::navbar.link>
   
    <!-- Optional Navbar End links -->
    <x-slot:end>
       ...
    </x-slot:end>

</x-ui::navbar>
```

### Sidebar
The `sidebar` component is a navigation component that contains  `links` using `x-ui::sidebar.link` elements. These accept standard anchor tag properties and an `active` property containing the name of the route this link navigates to, so that the current route can be highlighted, as well as an optional `icon` property containing the name of an `svg` icon. 

```
<x-ui.sidebar>
    <x-ui.sidebar.link href=".." icon="eye">Home</x-ui.sidebar.link>
</x-ui::sidebar>
```

The side bar contains a `title` slot that can be used to display e.g. an application logo and title and an `end` slot that can be used to display e.g. user info and/or user navigation links.

```
<x-ui.sidebar>
 <!-- Optional Sidebar title -->
    <x:slot:title>       
       ...
    </x:slot:title>

    <!-- Sidebar links -->
    <x-ui.sidebar.link href=".." icon="eye">...</x-ui.sidebar.link>
    
    <!-- Optional Sidebar end links -->
    <x-slot:end>
       ...
    </x-slot:end>
</x-ui.sidebar>
```

### Dropdown

A dropdown menu can be added to the `navbar` or `sidebar` using `navbar.drop` or `sidebar.drop`. A `title` property can be provided to name the menu or where a more complex `title` is required, replace the attribute with a title slot   `<x-slot:title>...</x-slot:title>`
The dropdown can then contains one or more `navbsr.link` or `sidebar.link` components.


```
<x-ui::navbar>
    ...
    <x-ui::navbar.drop title="Dropdown" icon="...">
        <x-ui::navbar.link active="about" href="..">...</x-ui::navbar.link>
        <x-ui::navbar.link active="home" href="..">...</x-ui::navbar.link>
    </x-ui::navbar.drop>
    ...
</x-ui::navbar>

<x-ui::sidebar>
    ...
    <x-ui::sidebar.drop>
        <x-slot:title>   
            ...                                                                         
        </x-slot:title>
        ...
    </x-ui::sidebar.drop>
    ...
</x-ui::sidebar>
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
#### Table Sort Link
A `link-sort` component is available for use in table header columns

```
<x-ui::link-sort name="attribute-name" />
```

#### Paginator
A `paginator` component is available to use when the table is displaying a paginated collection. The component also allows the user to specify the number of rows to display in each page. The paginated collection variable items should be passed via the `:items` property

```
<x-paginator :items="$collection" size="10" />
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

### Title / Header

The `title` component can be used to provide consistent page title, with configurable sizes `xl` (default), `lg`, `md`.

```
<x-ui::title size="lg">
    About Us
</x-ui::title>
```

The `header` component can be used with `title` to add a borderedflex area to the top of a page with title on left and optional navigation elements on right

```
<x-ui::header>
    <x-ui::title size="lg">
       Users
    </x-ui::title>

    <x::link href="#">Create</x::link>
</x-ui::header>
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

Svg component accepts a `variant` attribute, containing the name of the svg e.g. (`add` `add-user`, `user`, `arrow-left`, `arrow-right` `arrow-down` `arrow-up` `badge` `bars` `bars-up` `bars-down` `chevron-left` `chevron-right` `chevron-up` `chevron-down``chvron-up-down` `edit` `eye` `globe` `home` `info` `logo` `minus`
`pie` `plus` `tag` `trash`)

It also accepts a size attribute with values `sm` `md` `lg` and `xl`

```
<x-ui.svg variant="trash" size='sm' />
```
