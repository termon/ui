@props(['name', 'id' => null, 'value' => '', 'min' => null, 'max' => null, 'locale' => 'en-GB'])
<x-ui::form.date-picker :name="$name" :id="$id" :value="$value" :min="$min" :max="$max" :locale="$locale" :datetime="false" {{ $attributes }} />
