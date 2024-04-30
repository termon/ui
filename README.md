# Laravel View Components

A simple set of anonymous Laravel Blade View Components to help construct basic user interfaces. Components include:

    1. NavBar with nav item and drop down menu items
    2. Table
    3. Form components
    3. Common Svg Icons
    4. General components including: badge, breadcrumb, button, card, display, flash, page title, modal.

## Installation

Add the github repository to your composer.json file

```
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/termon/ui"
    }
],
```

Then use composer to install the library.
```
$ composer require termon/ui
```

## Prerequisite
These components require installation of [Tailwind CSS](https://tailwindcss.com) fpr styling and [AlpineJS](https:://alpinejs.dev) for interactivity.

## Using Components

The component prefix is ```ui``` followed by the name of the component (separated by :: )


```
<x-ui::<component-name>
```

## Available Components

### Nav Bar
Nav items accept standard anchor tag ```href``` property and the ```active``` property should be the name of the route so that current route can be highlighted.
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

### Button and Link
The ```button``` and ```link``` components can be configured with several variants ('blue', 'red', 'green', 'yellow', 'dark', 'light', 'oblue', 'ored', 'link')  

Following examples provide a Light variant button, and standard link. Adding a variant property to the link allows it to be styled as one of the buttons.

 ```
<x-ui::button variant="light">Light</button>
<x-ui::link>Link</button>
 ```


### Card
The ```Card``` component acts as a container for content. 

```
<x-ui::card>
   // ... card content
</x-ui::card>
```

Cards can also be configured with optional ```title``` and ```footer``` slots

```
<x-ui::card>
   <x-slot:title>Card Title</x-slot:title>

    // card content ...

   <x-slot:footer>Footer area</x-slot:footer>
</x-ui::card>
```

### Table
The ```Table``` component includes ```thead``` and ```tbody``` named slots, with ```th, tr, td``` elements. Each element will accept custom classes. An example table:

```
<x-ui::table>
    <x-slot:thead class="text-left">
        <x-ui::table.tr>
            <x-ui::table.th>Head Col</x-ui::table.th>            
        </x-ui::table.tr>
    </x-slot:thead>

    <x-slot:tbody>
        <x-ui::table.tr>            
            <x-ui::table.td>Row col</x-ui::table.td>
        </x-ui::table.tr>
        @endforeach
    </x-slot:tbody>
</x-ui::table>
```

### Form

Form elements include

1. label, input, select, textarea and error (validation) components
2. input-group and input-file-group, textarea-group components that are wrappers around label input and error components

Example usage:
The ```label, input, input-file, select, textarea``` components can be used individually. All accept standard html properties. 

#### Label
```
<x-ui::form.label>Label</x-ui::form.label>
```
#### Input
```
<x-ui::form.input name=".." value=".." />
```

#### Error
Note that the ```name``` property must match the name of the associated input component
```
<x-ui::form.error name=".." />
```
#### Select
The select component accepts a name and options(select list) property
```
```

#### Input Groups
Input groups are wrappers around the label, input and error components.
```
<x-ui::form.<type>-group label="Title" name="title" value="..." class="mb-4" />
```
where ```type``` is one of ```input, input-file, select, textarea```. 


### Flash
A flash component is used to display flash messages before a redirect. A controller action would typically flash a message to the session as part of the redirect.

```
return redirect()->route("..")
                 ->with('<type>', "<message>");  
```
where ```type``` is one of ```success, error, info, warning``` and ```message``` is the message to display.

The ```flash``` component should be rendered as part of the main layout 

```
<x-ui::flash />
<main>
      {{ $slot }}
</main>
```

### Breadcrumb

A breadcrumb can be used to aid navigation. To configure a breadcrumb component we should pass the crumbs as an associative array containing crumb name and a route. The final crumb typically has no associated route as it represents the current page.

```
<x-ui::breadcrumb class="my-3" :crumbs="[
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

### Title
A simple component to provide consistent page title 

```
<x-ui::title>
    About Us
</x-ui::title>
```