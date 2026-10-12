function createDatePicker(options) {
    return {
        value: options.value ?? '', text: '', open: false, cursor: '', month: '',
        panelStyle: { visibility: 'hidden' },
        localNow() { const date = new Date(); return new Date(date.getTime() - date.getTimezoneOffset() * 60000); },
        pad(n) { return String(n).padStart(2, '0'); },
        dateKey(date) { return `${String(date.getUTCFullYear()).padStart(4, '0')}-${this.pad(date.getUTCMonth() + 1)}-${this.pad(date.getUTCDate())}`; },
        parse(raw) {
            const match = String(raw ?? '').match(options.datetime
                ? /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})(?::(\d{2}))?$/
                : /^(\d{4})-(\d{2})-(\d{2})$/);
            if (!match) return null;
            const [, y, m, d, h = '0', min = '0', sec = '0'] = match;
            const date = new Date(0);
            date.setUTCFullYear(Number(y), Number(m) - 1, Number(d));
            date.setUTCHours(Number(h), Number(min), Number(sec), 0);
            return Number(y) >= 1 && date.getUTCFullYear() === Number(y) && date.getUTCMonth() === Number(m) - 1
                && date.getUTCDate() === Number(d) && date.getUTCHours() === Number(h)
                && date.getUTCMinutes() === Number(min) && date.getUTCSeconds() === Number(sec) ? date : null;
        },
        format(date) {
            return this.dateKey(date) + (options.datetime ? ` ${this.pad(date.getUTCHours())}:${this.pad(date.getUTCMinutes())}:${this.pad(date.getUTCSeconds())}` : '');
        },
        allowed(raw) {
            const date = this.parse(raw);
            if (!date) return false;
            const bound = (value, upper) => {
                if (!value) return null;
                return this.parse(options.datetime && /^\d{4}-\d{2}-\d{2}$/.test(value)
                    ? value + (upper ? ' 23:59:59' : ' 00:00:00') : value);
            };
            const min = bound(options.min, false), max = bound(options.max, true);
            return (!min || date >= min) && (!max || date <= max);
        },
        locked() { return this.$refs.input.disabled || this.$refs.input.readOnly; },
        init() {
            this.sync();
            this.$watch('value', () => { if (!this._editing && String(this.value ?? '') !== this.text) this.sync(); });
            this._reposition = () => { if (this.open) this.position(); };
            this._reset = () => this.$nextTick(() => { this.value = options.value ?? ''; this.sync(); this.close(false); });
            window.addEventListener('resize', this._reposition);
            document.addEventListener('scroll', this._reposition, true);
            this.$refs.input.form?.addEventListener('reset', this._reset);
        },
        destroy() {
            window.removeEventListener('resize', this._reposition);
            document.removeEventListener('scroll', this._reposition, true);
            this.$refs.input.form?.removeEventListener('reset', this._reset);
        },
        sync() {
            this.text = String(this.value ?? '');
            const date = this.parse(this.value) ?? this.localNow();
            this.cursor = this.dateKey(date);
            this.month = this.cursor.slice(0, 7);
        },
        edit() {
            if (this.locked()) return;
            this._editing = true;
            this.$nextTick(() => { this._editing = false; });
            const date = this.parse(this.text);
            this.value = date ? this.format(date) : this.text;
            if (date) {
                this.cursor = this.dateKey(date);
                this.month = this.cursor.slice(0, 7);
            }
        },
        get title() {
            return new Intl.DateTimeFormat(options.locale, { month: 'long', year: 'numeric', timeZone: 'UTC' }).format(this.dayDate(this.month + '-01'));
        },
        dayDate(key) {
            const date = new Date(0);
            const [y, m, d] = key.split('-').map(Number);
            date.setUTCFullYear(y, m - 1, d);
            date.setUTCHours(12, 0, 0, 0);
            return date;
        },
        get weekdays() {
            return Array.from({ length: 7 }, (_, i) => new Intl.DateTimeFormat(options.locale, { weekday: 'short' }).format(new Date(2024, 0, 7 + i)));
        },
        get cells() {
            const first = this.dayDate(this.month + '-01');
            const count = this.dayDate(this.month + '-01');
            count.setUTCMonth(count.getUTCMonth() + 1, 0);
            return Array.from({ length: first.getUTCDay() + count.getUTCDate() }, (_, i) => {
                if (i < first.getUTCDay()) return { key: `blank-${i}`, blank: true };
                const key = this.month + '-' + this.pad(i - first.getUTCDay() + 1);
                return { key, day: i - first.getUTCDay() + 1, blank: false };
            });
        },
        candidate(key) {
            return key + (options.datetime ? ' ' + (this.parse(this.value) ? this.format(this.parse(this.value)).slice(11) : '00:00:00') : '');
        },
        dayAllowed(key) {
            if (!options.datetime) return this.allowed(key);
            const minDay = options.min?.slice(0, 10), maxDay = options.max?.slice(0, 10);
            return (!minDay || key >= minDay) && (!maxDay || key <= maxDay);
        },
        choose(key) {
            if (this.locked() || !this.dayAllowed(key)) return;
            let raw = this.candidate(key);
            if (!this.allowed(raw)) {
                raw = options.min && key === options.min.slice(0, 10) ? options.min : options.max;
            }
            if (!this.allowed(raw)) return;
            this.value = this.format(this.parse(raw));
            this.sync();
            if (!options.datetime) this.close();
        },
        changeTime(raw) {
            if (this.locked()) return;
            const key = this.parse(this.value) ? this.value.slice(0, 10) : this.cursor;
            this.text = key + ' ' + raw;
            this.edit();
        },
        clear() { if (!this.locked()) { this.value = ''; this.sync(); this.close(); } },
        today() { if (!this.locked()) { const raw = this.format(this.localNow()); if (this.allowed(raw)) { this.value = raw; this.sync(); } } },
        show() {
            if (this.locked()) return;
            this.panelStyle = { visibility: 'hidden' };
            this.open = true;
            window.requestAnimationFrame(() => {
                if (!this.open) return;
                this.position();
                window.requestAnimationFrame(() => { if (this.open) this.focusDay(); });
            });
        },
        close(focus = true) { this.open = false; if (focus) this.$nextTick(() => this.$refs.input.focus()); },
        focusDay() { this.$refs.panel.querySelector(`[data-day="${this.cursor}"]`)?.focus(); },
        shiftMonth(amount) {
            const date = this.dayDate(this.cursor), day = date.getUTCDate();
            date.setUTCDate(1); date.setUTCMonth(date.getUTCMonth() + amount);
            const last = new Date(date.getTime()); last.setUTCMonth(last.getUTCMonth() + 1, 0);
            date.setUTCDate(Math.min(day, last.getUTCDate()));
            if (date.getUTCFullYear() < 1 || date.getUTCFullYear() > 9999) return;
            this.cursor = this.dateKey(date); this.month = this.cursor.slice(0, 7);
            this.$nextTick(() => { this.position(); this.focusDay(); });
        },
        navigate(event) {
            const deltas = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 };
            if (event.key === 'PageUp' || event.key === 'PageDown') {
                event.preventDefault(); this.shiftMonth((event.key === 'PageUp' ? -1 : 1) * (event.shiftKey ? 12 : 1)); return;
            }
            const date = this.dayDate(this.cursor);
            const delta = event.key === 'Home' ? -date.getUTCDay() : event.key === 'End' ? 6 - date.getUTCDay() : deltas[event.key];
            if (delta === undefined) return;
            event.preventDefault(); date.setUTCDate(date.getUTCDate() + delta);
            if (date.getUTCFullYear() < 1 || date.getUTCFullYear() > 9999) return;
            this.cursor = this.dateKey(date); this.month = this.cursor.slice(0, 7);
            this.$nextTick(() => { this.position(); this.focusDay(); });
        },
        position() {
            const rect = this.$refs.input.getBoundingClientRect(), panel = this.$refs.panel, gap = 8;
            const left = Math.max(gap, Math.min(rect.left, window.innerWidth - panel.offsetWidth - gap));
            const top = rect.bottom + gap + panel.offsetHeight <= window.innerHeight - gap
                ? rect.bottom + gap : Math.max(gap, rect.top - panel.offsetHeight - gap);
            this.panelStyle = { visibility: 'visible', top: `${top}px`, left: `${left}px` };
        },
    };
}
