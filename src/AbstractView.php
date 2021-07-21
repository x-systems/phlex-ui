<?php

declare(strict_types=1);

namespace Phlex\Ui;

use Phlex\Core\AppScopeTrait;
use Phlex\Core\ContainerTrait;
use Phlex\Core\DiContainerTrait;
use Phlex\Core\InitializerTrait;
use Phlex\Core\TrackableTrait;

/**
 * Abstract view tree item (used only for View and Callback, you want probably to extend one of these).
 *
 * @property View[] $elements
 *
 * @method View getOwner()
 */
abstract class AbstractView
{
    use AppScopeTrait;
    use ContainerTrait {
        add as doAdd;
    }
    use DiContainerTrait;
    use InitializerTrait;
    use TrackableTrait;
    use View\StaticAddViewTrait;

    /**
     * Default name of the element.
     *
     * @var string
     */
    public $defaultName = 'atk';

    /**
     * If addView() method is called, but current view is not part of render tree yet,
     * then arguments to addView() are simply stored in this array. When the view is
     * initialized by calling doInitialize() or adding into App or another initialized View,
     * then addView() will be re-invoked with the contents of this array.
     *
     * @var array
     */
    protected $addLaterArgs = [];

    /**
     * will be set to true after rendered. This is so that we don't render view twice.
     *
     * @var bool
     */
    protected $_rendered = false;

    // }}}

    // {{{ Default doInitialize() method and add() logic

    /**
     * For the absence of the application, we would add a very
     * simple one.
     */
    protected function initDefaultApp()
    {
        $this->setApp(new Webpage([
            'skin' => $this->skin,
            'catch_exceptions' => false,
            'always_run' => false,
            'catch_runaway_callbacks' => false,
        ]));
    }

    /**
     * Called when view becomes part of render tree. You can override it but avoid
     * placing any "heavy processing" here.
     */
    protected function doInitialize(): void
    {
        if (!$this->issetApp()) {
            $this->initDefaultApp();
        }

        if ($this->name === null) {
            $this->name = $this->defaultName;
        }

        // add default objects
        foreach ($this->addLaterArgs as [$object, $args]) {
            $this->addView($object, $args);
        }
        $this->addLaterArgs = [];
    }

    /**
     * @param AbstractView $object
     */
    public function addView($object, $args = null): self
    {
        (self::class)::assertInstanceOf($object);

        if (func_num_args() > 2) { // prevent bad usage
            throw new \Error('Too many method arguments');
        } elseif ($this->_rendered) {
            throw new Exception('You cannot add anything into the view after it was rendered');
        }

        if (!$this->issetApp()) {
            $this->addLaterArgs[] = [$object, $args];

            return $object;
        }

        // will call doInitialize() of the object
        return $this->doAdd($object, $args);
    }

    // }}}

    // {{{ Sticky URLs

    /** @var string[] stickyGet arguments */
    public $stickyArgs = [];

    /**
     * Build an URL which this view can use for js call-backs. It should
     * be guaranteed that requesting returned URL would at some point call
     * $this->initialize().
     *
     * @param array $page
     *
     * @return string
     */
    public function jsUrl($page = [])
    {
        return $this->getApp()->jsUrl($page, false, $this->_getStickyArgs());
    }

    /**
     * Build an URL which this view can use for call-backs. It should
     * be guaranteed that requesting returned URL would at some point call
     * $this->initialize().
     *
     * @param string|array $page URL as string or array with page name as first element and other GET arguments
     *
     * @return string
     */
    public function url($page = [])
    {
        return $this->getApp()->url($page, false, $this->_getStickyArgs());
    }

    /**
     * Get sticky arguments defined by the view and parents (including API).
     */
    protected function _getStickyArgs(): array
    {
        if ($this->issetOwner() && $this->getOwner() instanceof self) {
            $stickyArgs = array_merge($this->getOwner()->_getStickyArgs(), $this->stickyArgs);
        } else {
            $stickyArgs = $this->stickyArgs;
        }

        /** @var self $childView */
        $childView = $this->mergeStickyArgsFromChildView();
        if ($childView !== null && (!($childView instanceof Callback) || $childView->isTriggered())) {
            $alreadyCalled = false;
            foreach (debug_backtrace(\DEBUG_BACKTRACE_PROVIDE_OBJECT | \DEBUG_BACKTRACE_IGNORE_ARGS) as $frame) {
                if ($childView === ($frame['object'] ?? null) && $frame['function'] === '_getStickyArgs') {
                    $alreadyCalled = true;
                }
            }

            if (!$alreadyCalled) {
                $stickyArgs = array_merge($stickyArgs, $childView->_getStickyArgs());
            }
        }

        return $stickyArgs;
    }

    protected function mergeStickyArgsFromChildView(): ?self
    {
        return null;
    }

    /**
     * Mark GET argument as sticky. Calling url() on this view or any
     * sub-views will embedd the value of this GET argument.
     *
     * If GET argument is empty or false, it won't make into URL.
     *
     * If GET argument is not presently set you can specify a 2nd argument
     * to forge-set the GET argument for current view and it's sub-views.
     */
    public function stickyGet(string $name, string $newValue = null): ?string
    {
        $this->stickyArgs[$name] = $newValue ?? $_GET[$name] ?? null;

        return $this->stickyArgs[$name];
    }

    // }}}
}
