// Set PLAYWRIGHT_MODULE and ALPINE_SCRIPT; optionally set CHROME_PATH.
const assert = require('node:assert/strict');
const { execFileSync } = require('node:child_process');
const path = require('node:path');
const { chromium } = require(process.env.PLAYWRIGHT_MODULE || 'playwright');

(async () => {
    const browser = await chromium.launch({ headless: true, ...(process.env.CHROME_PATH ? { executablePath: process.env.CHROME_PATH } : {}) });
    try {
        const page = await browser.newPage();
        const errors = [];
        page.on('pageerror', error => errors.push(error.message));
        const html = execFileSync('php', [path.join(__dirname, 'render-date-picker.php')], { encoding: 'utf8' });
        await page.setContent(html);
        if (process.env.LIVEWIRE_SCRIPT) {
            await page.evaluate(() => { window.livewireScriptConfig = { csrf: 'fixture', uri: '/livewire/update', progressBar: 'data-no-progress-bar' }; });
            await page.addScriptTag({ path: process.env.LIVEWIRE_SCRIPT });
            await page.evaluate(() => Livewire.start());
        } else {
            await page.addScriptTag({ path: process.env.ALPINE_SCRIPT });
        }
        const date = page.locator('#date');
        await date.fill('2026-02-30');
        assert.equal(await date.inputValue(), '2026-02-30');
        assert.equal(await date.evaluate(el => el.validationMessage), '');
        assert.equal(await page.locator('input[name=date]').inputValue(), '2026-02-30');
        assert.equal(await page.locator('form').evaluate(el => el.checkValidity()), true);
        await date.fill('2026-10-15');
        await date.click();
        assert.equal(await page.locator('[role=dialog]').first().evaluate(el => el.hidden), true);
        await page.getByRole('button', { name: 'Open calendar', exact: true }).first().click();
        await page.waitForFunction(() => document.activeElement?.dataset.day === '2026-10-15');
        await page.locator('[data-day="2026-10-15"]').first().press('PageDown');
        await page.waitForFunction(() => document.activeElement?.dataset.day === '2026-11-15');
        await page.getByRole('button', { name: 'Next year', exact: true }).first().click();
        await page.waitForFunction(() => document.activeElement?.dataset.day === '2027-11-15');
        await page.getByRole('button', { name: 'Previous year', exact: true }).first().click();
        await page.waitForFunction(() => document.activeElement?.dataset.day === '2026-11-15');
        await page.locator('[data-day="2026-11-15"]').first().press('Enter');
        assert.equal(await date.inputValue(), '2026-11-15');
        const datetime = page.locator('#datetime');
        assert.equal(await datetime.inputValue(), '2026-10-12 09:02:17');
        await page.getByRole('button', { name: 'Open calendar', exact: true }).nth(1).click();
        const datetimePanel = page.locator('[role=dialog]').nth(1);
        await datetimePanel.locator('[data-day="2026-10-15"]').click();
        await datetimePanel.locator('input[type=time]').fill('11:03:19');
        await datetimePanel.locator('input[type=time]').dispatchEvent('change');
        assert.equal(await datetime.inputValue(), '2026-10-15 11:03:19');
        await datetimePanel.getByRole('button', { name: 'Close', exact: true }).click();
        assert.equal(await date.inputValue(), '2026-11-15');
        await page.getByRole('button', { name: 'Reset', exact: true }).click();
        await page.waitForFunction(() => document.querySelector('#date').value === '2026-10-12');
        // Emulate an external model update, then insert another picker after Alpine has started.
        await page.locator('[x-modelable]').first().evaluate(el => { Alpine.$data(el).value = '2024-02-29'; });
        await page.waitForFunction(() => document.querySelector('#date').value === '2024-02-29');
        await page.evaluate(html => {
            const source = new DOMParser().parseFromString(html, 'text/html').querySelector('[x-modelable]');
            const copy = document.createElement('div');
            copy.innerHTML = source.outerHTML;
            const picker = copy.firstElementChild;
            picker.querySelector('input[type=text]').id = 'dynamic-date';
            document.body.append(picker);
        }, html);
        await page.locator('#dynamic-date').evaluate(el => el.closest('[x-modelable]').querySelector('button[aria-label="Open calendar"]').click());
        await page.waitForFunction(() => [...document.querySelectorAll('[role=dialog]')].some(el => !el.hidden));
        await page.locator('#dynamic-date').evaluate(el => el.closest('[x-modelable]').remove());
        assert.deepEqual(errors, []);
        console.log('Date picker browser checks passed.');
    } finally {
        await browser.close();
    }
})().catch(error => { console.error(error); process.exitCode = 1; });
