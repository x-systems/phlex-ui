<?php
/**
 * Trait for Control that use Vue Lookup component.
 */

declare(strict_types=1);

namespace Phlex\Ui\Form\Control;

use Phlex\Ui\Callback;

trait VueLookupTrait
{
    /** @var Callback */
    public $dataCallback;

    public function initVueLookupCallback(): void
    {
        if (!$this->dataCallback) {
            $this->dataCallback = Callback::addTo($this);
        }

        $this->dataCallback->set(\Closure::fromCallable([$this, 'outputApiResponse']));
    }

    /**
     * Output lookup search query data.
     *
     * @return never
     */
    public function outputApiResponse()
    {
        $data = [];
        if ($key = $_GET['phlex_vlookup_field'] ?? null) {
            $query = $_GET['phlex_vlookup_q'] ?? null;
            $reference = $this->getModel()->getField($key);
            $theirModel = $reference->createTheirModel();
            $theirKey = $reference->getTheirKey();
            if (!empty($query)) {
                $theirModel->addCondition($theirModel->titleKey, 'like', '%' . $query . '%');
            }
            foreach ($theirModel as $row) {
                $data[] = ['key' => $row->get($theirKey), 'text' => $row->getTitle(), 'value' => $row->get($theirKey)];
            }
        }

        $this->getApp()->terminateJson([
            'success' => true,
            'results' => $data,
        ]);
    }
}
