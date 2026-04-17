import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static values = {
        accordionId: String,
    }

    connect() {
        this.boundHandlers = {
            show: (event) => this.forwardEvent('show', event),
            shown: (event) => this.forwardEvent('shown', event),
            hide: (event) => this.forwardEvent('hide', event),
            hidden: (event) => this.forwardEvent('hidden', event),
        };

        this.element.addEventListener('show.bs.collapse', this.boundHandlers.show);
        this.element.addEventListener('shown.bs.collapse', this.boundHandlers.shown);
        this.element.addEventListener('hide.bs.collapse', this.boundHandlers.hide);
        this.element.addEventListener('hidden.bs.collapse', this.boundHandlers.hidden);
    }

    disconnect() {
        if (!this.boundHandlers) {
            return;
        }

        this.element.removeEventListener('show.bs.collapse', this.boundHandlers.show);
        this.element.removeEventListener('shown.bs.collapse', this.boundHandlers.shown);
        this.element.removeEventListener('hide.bs.collapse', this.boundHandlers.hide);
        this.element.removeEventListener('hidden.bs.collapse', this.boundHandlers.hidden);
    }

    forwardEvent(name, event) {
        const panel = event.target;
        const detail = {
            accordionId: this.accordionIdValue || this.element.id || null,
            panelId: panel.dataset.accordionPanelId || panel.id || null,
            index: panel.dataset.accordionPanelIndex ? Number(panel.dataset.accordionPanelIndex) : null,
            title: panel.dataset.accordionPanelTitle || null,
        };

        this.element.dispatchEvent(new CustomEvent(`tabler:accordion:${name}`, {
            bubbles: true,
            detail,
        }));
    }
}
