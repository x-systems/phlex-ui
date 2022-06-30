import $ from 'jquery';
import phlexPlugin from './phlex.plugin';

export default class spinner extends phlexPlugin {
    main() {
        this.timer = null;
        const options = this.settings;
        // Remove any existing dimmers/spinners
        this.$el.remove('.dimmer');
        this.$el.remove('.spinner');

        const $baseDimmer = $(options.baseDimmerMarkup);
        const $baseLoader = $(options.baseLoaderMarkup);

        let $finalSpinner = null;

        $baseLoader.toggleClass('active', options.active);
        $baseLoader.toggleClass('indeterminate', options.indeterminate);
        $baseLoader.toggleClass('centered', options.centered);
        $baseLoader.toggleClass('inline', options.inline);

        const isText = !!(options.loaderText);
        if (isText) {
            $baseLoader.toggleClass('text', true);
            $baseLoader.text(options.loaderText);
        }

        if (options.dimmed) {
            $baseDimmer.toggleClass('active', options.active);
            $finalSpinner = $baseDimmer.append($baseLoader);
        } else {
            $finalSpinner = $baseLoader;
        }

        // If replace is true we remove the existing content in the $element.
        this.showSpinner(this.$el, $finalSpinner, options.replace);
    }

    showSpinner($element, $spinner, replace = false) {
        this.timer = setTimeout(() => {
            if (replace) $element.empty();
            $element.append($spinner);
        }, 500);
    }

    remove() {
        clearTimeout(this.timer);
        this.$el.find('.loader').remove();
    }
}

spinner.DEFAULTS = {
    active: false,
    replace: false,
    dimmed: false,
    inline: false,
    indeterminate: false,
    loaderText: 'Loading',
    centered: false,
    baseDimmerMarkup: '<div class="ui dimmer"></div>',
    baseLoaderMarkup: '<div class="ui loader"></div>',
};
