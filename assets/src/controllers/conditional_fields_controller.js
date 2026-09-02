import { Controller } from '@hotwired/stimulus';

/*
 * Show or hide form rows based on another field's value.
 *
 * Put the controller on a form (or any wrapper) and mark the rows that depend on
 * something:
 *
 *   data-depends-on="existingSystem"   the field's name (last segment, not the full path)
 *   data-show-if="other"               space-separated values that reveal the row
 *   data-show-if="!none"               or a negation: any value except this one
 *
 * A checkbox reports "1" when checked and "" when not, so data-show-if="1" tracks it.
 *
 * Hidden rows keep their inputs in the DOM and still submit -- with an unchanged default,
 * which is what the server expects. Nothing here validates; it only decides what is worth
 * showing, so a form with JS off asks more questions than it needs but still works.
 */
export default class extends Controller {
    connect() {
        this.rows = Array.from(this.element.querySelectorAll('[data-depends-on]'));
        if (this.rows.length === 0) {
            return;
        }

        this.onChange = () => this.apply();
        this.element.addEventListener('change', this.onChange);
        this.element.addEventListener('input', this.onChange);
        this.apply();
    }

    disconnect() {
        this.element.removeEventListener('change', this.onChange);
        this.element.removeEventListener('input', this.onChange);
    }

    apply() {
        for (const row of this.rows) {
            const value = this.valueOf(row.dataset.dependsOn);
            row.hidden = !this.matches(value, row.dataset.showIf ?? '');
        }
    }

    /** Current value of the named field, however it is rendered. */
    valueOf(name) {
        // Field names arrive as tenant_onboarding_flow[tenant][existingSystem]; matching
        // the last segment keeps the markup readable and survives the form being nested
        // one level deeper.
        const inputs = Array.from(this.element.querySelectorAll('input, select, textarea')).filter(
            (input) => input.name.endsWith(`[${name}]`) || input.name === name,
        );

        for (const input of inputs) {
            if (input.type === 'checkbox') {
                return input.checked ? input.value || '1' : '';
            }
            // Radios: only the checked one speaks.
            if (input.type === 'radio' && !input.checked) {
                continue;
            }
            return input.value;
        }

        return '';
    }

    matches(value, spec) {
        const wanted = spec.trim().split(/\s+/).filter(Boolean);
        if (wanted.length === 0) {
            return true;
        }

        return wanted.some((want) =>
            want.startsWith('!') ? value !== want.slice(1) : value === want,
        );
    }
}
