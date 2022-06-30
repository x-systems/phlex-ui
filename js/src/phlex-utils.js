import mitt from 'mitt';
import 'helpers/url.helper';

/**
 * Define phlex global options.
 * In Js:
 *  phlex.options.set('name','value');
 * In Php:
 *  (new JsChain('phlex.options')->set('name', 'value');
 */
const phlexOptions = (function () {
    const options = {
    // Value for debounce time out (in ms) that will be apply globally when set using phlex.debounce.
        debounceTimeout: null,
    };
    return {
        set: (name, value) => { options[name] = value; },
        get: (name) => options[name],
    };
}());

/**
 * Subscribe too and publish events.
 * listen to an event
 *   phlex.eventBus.on('foo', e => console.log('foo', e))
 * Fire an event
 *   phlex.eventBus.emit('foo', { a: 'b' })
 *
 */
const phlexEventBus = (function () {
    const eventBus = mitt();
    return {
        emit: (event, payload) => eventBus.emit(event, payload),
        on: (event, ref) => eventBus.on(event, ref),
        off: (event, ref) => eventBus.off(event, ref),
        clearAll: () => eventBus.all.clear(),
    };
}());

/*
* Utilities function that you can execute
* from phlex context. Usage: phlex.utils.date().parse('string');
*/
const phlexUtils = (function () {
    return {
        json: function () {
            return {
                // try parsing string as JSON. Return parse if valid, otherwise return onError value.
                tryParse: function (str, onError = null) {
                    try {
                        return JSON.parse(str);
                    } catch (e) {
                        return onError;
                    }
                },
            };
        },
        date: function () {
            return {
                // fix date parsing for different time zone if time is not supply.
                parse: function (dateString) {
                    if (dateString.match(/^[0-9]{4}[/\-.][0-9]{2}[/\-.][0-9]{2}$/)) {
                        dateString += ' 00:00:00';
                    }
                    return dateString;
                },
            };
        },
    };
}());

export { phlexOptions, phlexEventBus, phlexUtils };
