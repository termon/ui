# Laravel View Components

> **Version 1.6.3**

A simple set of anonymous Laravel Blade View Components using TailwindCSS 4 for stylin , to help construct basic user interfaces. 

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
Add following line to your `app.css` file

`@source '../../vendor/termon/ui/src/resources/views/components/**/*.blade.php';`

## Using Components

The component prefix is `x-ui` followed by the name of the component (separated by :: or . when installed locally )

```
<x-ui::<component-name> // using vendor package
<x-ui.<component-name>  // when published locally (recommended)
```

## Available Components

### Heading

The heading component provides styled headings with consistent light/dark mode styling and mobile support. The component takes a level parameter with a value between `1-6`

```
<x-ui::heading level="1">Heading Level 1</x-ui::heading>
<x-ui::heading level="2">Heading Level 2</x-ui::heading>
```

### Navbar

The `navbar` component is a fully responsive (mobile friendly) navigation component that contains  `links` using `x-ui::navbar.link` elements. These accept standard anchor tag properties and an `active` property containing the name of the route this link navigates to, so that the current route can be highlighted, as well as an optional `icon` property containing the name of an `svg` icon. Links can be grouped into a dropdown `<x-ui::navbar.dropdown icon=".." label=".."> ... </x-ui::navbar.dropdown>`
```
<x-ui::navbar>

    <!-- Optional Navbar Title -->
    <x-slot:title>
       ...
    </x-slot:title>

    <!-- Navbar links -->
    <x-slot:navigation>
        <x-ui::navbar.link active="home" href=".."> ... </x-ui::navbar.link>
    </x-slot:navigation>
   
    <!-- Optional Navbar Right links -->
    <x-slot:right>
       ...
    </x-slot:right>

    <!-- Optional Navbar Bottom links (visible in mobile mode )-->
    <x-slot:bottom>
        ...
    </x-slot:bottom>

</x-ui::navbar>
```

### Sidebar
The `sidebar` component is a fully responsive (mobile friendly) navigation component that contains  `links` using `x-ui::sidebar.link` elements. These accept standard anchor tag properties and an `active` property containing the name of the route this link navigates to, so that the current route can be highlighted, as well as an optional `icon` property containing the name of an `svg` icon. Links can be grouped into a dropdown `<x-ui::sidebar.dropdown icon=".." label=".."> ... </x-ui::sidebar.dropdown>`

```
<x-ui::sidebar>
 <!-- Optional Sidebar title -->
    <x-slot:title>       
       ...
    </x-slot:title>

    <!-- Sidebar navigation links -->
    <x-slot:navigation>
        <x-ui::sidebar.link href=".." icon="eye">...</x-ui::sidebar.link>
    </x-slot:navigation> 
    
    <!-- Optional Sidebar bottom links -->
    <x-slot:bottom>
       ...
    </x-slot:bottom>

    <!-- Optional Sidebar top links - displayed when in mobile mode -->
    <x-slot:top>
        ...
    </x-slot:top>

</x-ui.sidebar>
```

### Dropdown

A dropdown menu can be added to the `navbar` or `sidebar` using `navbar.dropdown` or `sidebar.dropdown`. A `label` property can be provided to name the menu and an optional `icon` property can be used to add an `svg` icon. 


```
<x-ui::navbar.dropdown label="Dropdown" icon="...">
    <x-ui::navbar.link active="about" href="..">...</x-ui::navbar.link>
    <x-ui::navbar.link active="home" href="..">...</x-ui::navbar.link>
</x-ui::navbar.dropdown>
```

#### Navbar/Sidebar Link

Both `navbar` and `sidebar` contain a `link` component that is used to display navlinks.

```
<x-ui::navbar.link icon="..." label="..." href="..."  />
<x-ui::sidebar.link icon="..." label="..." href="..."  />
```
Icons and labels are optional. Additionally a `method` parameter allows a link to use another http method (useful when the link carries out an action such as logout)

### Button and Link

The `button` and `link` components can be configured with several variants (`'blue'`, `'red'`, `'green'`, `'yellow'`, `'dark'`, `'light'`, `'oblue'`, `'ored'`, `'link'`)

Following examples provide a `'light'` variant button, and standard `link`. Adding a `variant` property to the link allows it to be styled as one of the buttons.

```
<x-ui::button variant="light">Light</button>
<x-ui::link>Link</button>
<x-ui::link variant='oblue'>Outline Blue Link</button>
```

Both also accept optional `icon` and `label` properties. For `icon` specify the name of an svg icon (see svg below). The `label` property can be used instead of the slot e.g.

```
<x-ui::button variant="light" icon="folder" label="Information"/>
```

> The component will display only the icon on `sm` screens and both on larger screens.


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

Table columns can optionally be configured to only be visible at specified breakpoints (useful when supporting mobile). Ensure matching header and body column have matching attributes.

In this example the column is only visible at `lg` and greater breakpoints
```
<x-ui::table.th showOn="lg">...</x-ui::table.th>

<x-ui::table.td showOn="lg">...</x-ui::table.td>

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

An optional set of `:options` can be added to override the default page options `:options="['15' => 15, '25' => 25, '50' => 50, '100' => 100, '500' => 500]"`. Additionally an optional `variant` can be used to specify a colour `green` `red` `dark` `purple` `light`

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

####

A custom date picker component is also available and can be used instead of form input with a date type.

```
<x-ui::form.date-picker name="birthday" label="Birthday" value="{{ $birthday }}"
```
> The input value should be a string in format Y-m-d

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

> The position of the flash message can be specified using an optional `position` parameter with values `top-right` `top-left` `bottom-right` `bottom-left`

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

#### Title 

The `title` component can be used to provide consistent page title, with configurable sizes `xl` (default), `lg`, `md`.

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

    <x::link href="#">Create</x::link>
</x-ui::header>
```

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
</x-ui.tabs.tab>
```

### Svg

Svg component accepts a `variant` attribute, containing the name of the svg e.g. ( `add` `add-user` `adjustments-horizontal` `avatar` `arrow-path` `arrow-right` `arrow-left` `arrow-up` `arrow-down` `archive-box` `badge` `bars` `bars-up` `bars-down` `bell` `check-circle` `chat-bubble-left` `cog-6-tooth` `chevron-left` `chevron-right` `chevron-up` `chevron-down` `chevron-up-down` `document-duplicate` `edit` `eye` `exit` `folder` `globe` `home` `info` `light-bulb` `list` `list-bullet` `link` `magnifying-glass` `minus` `moon` `pie` `plus` `tag` `trash` `user` `wrench` `x-mark`)

It also accepts a size attribute with values `sm` `md` `lg` and `xl`

```
<x-ui::svg variant="trash" size='sm' />
```

### Modal

A `modal` component is used to display a popup modal. The modal accepts a `name` prop which must be unique on the page containing the modal. It also can be configured with optional `title` and `footer` slots.
   
```
<x-ui::modal name="test"  focusable>
    <x-slot:title>
        ...
    </x-slot:title>

    <!-- modal content -->

    <x-slot:footer>
        ...
    </x-slot:footer>
</x-ui::modal>  
```

#### Trigger

To trigger a modal we can use a `button` and `@click` attribute to trigger the opening of the modal (named `test` in this example).

```
<x-ui::button variant="dark" @click="$dispatch('open-modal', 'test')">Open</x-ui::button>
```

### Steps

The `steps` component can be used to display a list of steps with their completion status

```
<x-ui::steps :steps="[1 => ['Details', true], 2 => ['Employment', true], 3 => ['Plan', true], 4 => ['Safety', false]]" />
```

The component also accepts optional parameters

`numbered` - displays step number
`percent` - displays a percentage completion bar beneath the steps
`variant` - colour of completed steps `green` `blue` `indigo` (default) 


### Highchart

A component wrapping the highchart library. The component slot should contain a string representing a javascript object describing the chart.

```
<x-ui::highchart>
{
    title: { text: 'Demo Chart' },
    series: [{ data: [1, 2, 3, 4] }]
}
</x-ui::highchart>
```

### Rating

The rating component requires a `value` parameter. The `max` parameter is optional and defaults to `5`. The `size` parameter can be `sm` `md` `lg` and defaults to `md`.  

```
<x-ui::rating value="3.5" size="md" max="5"  />
```