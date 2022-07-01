<?php

declare(strict_types=1);
/**
 * Shortcut handler for calling method of
 * the phlex javascript vue service.
 */

namespace Phlex\Ui;

class JsVueService
{
    /**
     * The phlex vue service to talk too.
     *
     * @var JsChain
     */
    public $service;

    /**
     * JsVueService constructor.
     */
    public function __construct()
    {
        $this->service = new JsChain('phlex.vueService');
    }

    /**
     * Create a new Vue instance using a component managed by Phlex UI.
     *
     * This output js: phlex.vueService.createPhlexVue("id", "component", {});
     *
     * @return mixed
     */
    public function createPhlexVue($id, $component, array $data = [])
    {
        return $this->service->createPhlexVue($id, $component, $data);
    }

    /**
     * Create a new Vue instance using an external component.
     * External component should be load via js file and define properly.
     *
     * This output js: phlex.vueService.createVue("id", "component", {});
     *
     * @return mixed
     */
    public function createVue($id, $componentName, $component, array $data = [])
    {
        return $this->service->createVue($id, $componentName, $component, $data);
    }

    /**
     * Make Vue aware of externally loaded components.
     * The component name must be accessible in javascript using the window namespace.
     * ex: window['SemanticUIVue'].
     *
     * @param string $component the component name to use with Vue
     *
     * @return mixed
     */
    public function useComponent($component)
    {
        return $this->service->useComponent($component);
    }
}
