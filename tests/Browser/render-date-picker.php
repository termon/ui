<?php

require dirname(__DIR__, 2).'/vendor/autoload.php';

$test = new class('runTest') extends \Termon\Ui\Tests\TestCase
{
    public function bootFixture(): void { $this->setUp(); }
    public function runTest(): void {}
};
$test->bootFixture();
echo \Illuminate\Support\Facades\Blade::render('<form><x-ui::form.date name="date" value="2026-10-12" required /><x-ui::form.datetime name="datetime" value="2026-10-12 09:02:17" /><button type="reset">Reset</button></form>');
