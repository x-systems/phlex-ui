/**
 *  Url helper jQuery functions.
 *
 * - AddParams - Pass an url with an object and object key=value pair will be
 *   added to the url as get parameter.
 *   ex: $.phlexAddParams('myurl.php', {q: 'test', 'reload': 'my_view'})
 *   will return: myurl.php?q=test&reload=my_view
 *
 * -RemoveParam - remove a parameter from an url string.
 *  ex: $.phlexRemoveParam('myurl.php?q=test&reload=my_view', 'q')
 *  will return: myurl.php?reload=my_view
 *
 */

(function ($) {
    if (!$.phlex) {
        $.phlex = {};
    }

    /**
     * Get the base url from string.
     *
     * @param url
     * @returns {*|string}
     */
    $.phlex.getUrl = function (url) {
        return url.split('?')[0];
    };

    /**
     * Get each url query parameter as a key:value pair object.
     *
     * @param str
     * @returns {{}|unknown}
     */
    $.phlex.getQueryParams = function (str) {
        if (str.split('?')[1]) {
            return decodeURIComponent(str.split('?')[1])
                .split('&')
                .reduce((obj, unsplitArg) => {
                    const arg = unsplitArg.split('=');
                    // eslint-disable-next-line prefer-destructuring
                    obj[arg[0]] = arg[1];
                    return obj;
                }, {});
        }
        return {};
    };

    /**
     * Add param to an url string.
     *
     * @param url
     * @param data
     * @returns {*}
     */
    $.phlex.addParams = function (url, data) {
        if (!$.isEmptyObject(data)) {
            url += (url.indexOf('?') >= 0 ? '&' : '?') + $.param(data);
        }

        return url;
    };

    /**
     * Remove param from an url string.
     *
     * @param url
     * @param param
     * @returns {string|*|string}
     */
    $.phlex.removeParam = function (url, param) {
        const splitUrl = url.split('?');
        if (splitUrl.length === 0) {
            return url;
        }

        const urlBase = splitUrl[0];
        if (splitUrl.length === 1) {
            return urlBase;
        }

        const newParams = splitUrl[1].split('&').filter((item) => item.split('=')[0] !== param);
        if (newParams.length > 0) {
            return urlBase + '?' + newParams.join('&');
        }
        return urlBase;
    };
}(jQuery));

export default (function ($) {
    $.phlexGetUrl = $.phlex.getUrl;
    $.phlexAddParams = $.phlex.addParams;
    $.phlexRemoveParam = $.phlex.removeParam;
    $.phlexGetQueryParam = $.phlex.getQueryParams;
}(jQuery));
