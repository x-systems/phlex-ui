import phlexPlugin from './phlex.plugin';
import apiService from '../services/api.service';

export default class serverEvent extends phlexPlugin {
    main() {
        const element = this.$el;
        const hasLoader = this.settings.showLoader;

        if (typeof (EventSource) !== 'undefined') {
            this.source = new EventSource(`${this.settings.uri}&__phlex_sse=1`);
            if (hasLoader) {
                element.addClass('loading');
            }

            this.source.onmessage = function (e) {
                apiService.phlexSuccessTest(JSON.parse(e.data));
            };

            this.source.onerror = (e) => {
                if (e.eventPhase === EventSource.CLOSED) {
                    if (hasLoader) {
                        element.removeClass('loading');
                    }
                    this.source.close();
                }
            };

            this.source.addEventListener('phlex_sse_action', (e) => {
                apiService.phlexSuccessTest(JSON.parse(e.data));
            }, false);

            if (this.settings.closeBeforeUnload) {
                window.addEventListener('beforeunload', (event) => {
                    this.source.close();
                });
            }
        } else {
            // console.log('server side event not supported fallback to phlexReloadView');
            this.$el.phlexReloadView({
                uri: this.settings.uri,
            });
        }
    }

    /**
   * To close ServerEvent.
   */
    stop() {
        this.source.close();
        if (this.settings.showLoader) {
            this.$el.removeClass('loading');
        }
    }
}

serverEvent.DEFAULTS = {
    uri: null,
    uri_options: {},
    showLoader: false,
    closeBeforeUnload: false,
};
