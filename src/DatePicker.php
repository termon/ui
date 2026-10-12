<?php

namespace Termon\Ui;

final class DatePicker
{
    public static function source(): string
    {
        return file_get_contents(__DIR__.'/resources/js/date-picker.js');
    }
}
