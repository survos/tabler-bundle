import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
/**
 * @target search_input  The search input; its value drives whether `categories` is shown.
 * @target categories    Category list toggled visible via the `show` class.
 * @action changed        Adds/removes the `show` class on `categories` based on whether `search_input` has a value.
 * @action blur           Debug hook that logs a warning when the search input loses focus.
 */
export default class extends Controller {
    static targets = ['search_input','categories']
    static values = {
        // slides: Array,
    }

    connect()
    {
        super.connect();
        let el = this.element;
        // if (this.hasSlideshowTarget) {
        //     // this.slideshowTarget.innerHTML = "…"
        //     // this.slideshowTarget.innerHTML = 'test';
        // } else {
        //     console.error('missing slideshowTarget');
        // }
        console.warn("hello from " + this.identifier);


    }

    changed(el) {
        if(el.target.value) {
            this.categoriesTarget.classList.add("show");
            // this.categoriesTarget.innerHTML = "Show!  switch the class or whatever";
        } else
            this.categoriesTarget.classList.remove("show");
    }

    blur(el) {
        console.warn('blur called',el);
    }
}
