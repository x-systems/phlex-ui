import Vue from 'vue';
import SuiVue from 'semantic-ui-vue';
import phlexClickOutside from '../directives/click-outside.directive';
import { focus } from '../directives/commons.directive';

Vue.use(SuiVue);

Vue.component('flat-picker', () => import('vue-flatpickr-component'));

// Vue loader component to display while dynamic component is loading.
const phlexVueLoader = {
    name: 'phlex-vue-loader',
    template: '<div><div class="ui active centered inline loader"></div></div>',
};

// Vue error component to display when dynamic component loading fail.
const phlexVueError = {
    name: 'phlex-vue-error',
    template: '<div class="ui negative message"><p>Error: Unable to load Vue component</p></div>',
};

// Return async component that will load on demand.
const componentFactory = (name, component) => () => ({
    component: component().then((r) => { phlex.vueService.markComponentLoaded(name); return r; }),
    loading: phlexVueLoader,
    error: phlexVueError,
    delay: 200,
});

const phlexComponents = {
    'phlex-inline-edit': componentFactory('phlex-inline-edit', () => import(/* webpackChunkName: "phlex-vue-inline-edit" */'../components/inline-edit.component')),
    'phlex-item-search': componentFactory('phlex-item-search', () => import(/* webpackChunkName: "phlex-vue-item-search" */'../components/item-search.component')),
    'phlex-multiline': componentFactory('phlex-multiline', () => import(/* webpackChunkName: "phlex-vue-multiline" */'../components/multiline/multiline.component')),
    'phlex-tree-item-selector': componentFactory('phlex-tree-item-selector', () => import(/* webpackChunkName: "phlex-vue-tree-item-selector" */'../components/tree-item-selector/tree-item-selector.component')),
    'phlex-query-builder': componentFactory('phlex-query-builder', () => import(/* webpackChunkName: "phlex-vue-query-builder" */'../components/query-builder/query-builder.component.vue')),
};

// setup phlex custom directives.
const phlexDirectives = [{ name: 'click-outside', def: phlexClickOutside }, { name: 'focus', def: focus }];
phlexDirectives.forEach((directive) => {
    Vue.directive(directive.name, directive.def);
});

/**
 * Singleton class
 * Create Vue component.
 */
class VueService {
    static getInstance() {
        return this.instance;
    }

    constructor() {
        if (!VueService.instance) {
            this.vues = [];
            this.vueMixins = {
                methods: {
                    getData: function () {
                        return this.initData;
                    },
                },
                // provide method to our child component.
                // child component would need to inject a method to have access using the inject property,
                // inject: ['getRootData'],
                // Once inject you can get initial data using this.getRootData().
                provide: function () {
                    return {
                        getRootData: this.getData,
                    };
                },
            };
            VueService.instance = this;
        }
        return VueService.instance;
    }

    /**
   * Created a Vue component and add it to the vues array.
   * For Root component (App) to be aware that it's children component is
   * mounted, you need to use @hook:mounted="setReady"
   * @param name
   * @param component
   * @param data
   */
    createPhlexVue(id, component, data) {
        this.registerComponent({
            ids: [id],
            name: component,
            instance: new Vue({
                el: id,
                data: { initData: data },
                components: { [component]: phlexComponents[component] },
                mixins: [this.vueMixins],
            }),
            isLoaded: false,
        });
    }

    /**
   * Create a Vue instance from an external src component definition.
   *
   * @param name
   * @param component
   * @param data
   */
    createVue(id, componentName, component, data) {
        this.registerComponent({
            ids: [id],
            name: componentName,
            instance: new Vue({
                el: id,
                data: { initData: data, isReady: true },
                components: { [componentName]: window[component] },
                mixins: [this.vueMixins],
            }),
            isLoaded: true,
        });
    }

    /*
    *  Add component to vues container.
    *  Group ids that are using the same component.
     */
    registerComponent(component) {
        // check if that component is already registered
        const registered = this.vues.filter((comp) => comp.name === component.name);
        if (registered.length > 0) {
            registered[0].ids.push(component.ids[0]);
        } else {
            this.vues.push(component);
        }
    }

    /**
   * Register components within Vue.
   */
    useComponent(component) {
        if (window[component]) {
            Vue.use(window[component]);
        } else {
            console.error('Unable to register component: ' + component + '. Make sure it is load correctly.');
        }
    }

    /**
   * Return Vue.
   *
   * @returns {Vue | VueConstructor}
   */
    getVue() {
        return Vue;
    }

    /*
    * Mark a component as loaded.
    */
    markComponentLoaded(name) {
        this.vues.forEach((component) => {
            if (component.name === name) {
                component.isLoaded = true;
            }
        });
    }

    /**
     * Check if all components on page are ready and fully loaded.
     */
    areComponentsLoaded() {
        return this.vues.filter((component) => component.isLoaded === false).length === 0;
    }
}

const vueService = new VueService();
Object.freeze(vueService);

export default vueService;
