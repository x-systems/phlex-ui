<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Hintable\HintablePropertyDef;
use Phlex\Data\Model;
use Phlex\Ui\Form;

try {
    if (file_exists(__DIR__ . '/db.php')) {
        require_once __DIR__ . '/db.php';
    } else {
        require_once __DIR__ . '/db.default.php';
    }
} catch (\PDOException $e) {
    // do not pass $e unless you can secure DSN!
    throw (new \Phlex\Ui\Exception('This demo requires access to the database. See "demos/init-db.php"'))
        ->addMoreInfo('PDO error', $e->getMessage());
}

// a very basic file that sets up Agile Data to be used in some demonstrations

class ModelWithPrefixedFields extends Model
{
    private function prefixKey(string $key, bool $forActualName = false): string
    {
        if ($forActualName) {
            $key = preg_replace('~^atk_fp_' . preg_quote($this->table, '~') . '__~', '', $key);
        }

        return 'atk_' . ($forActualName ? 'a' : '') . 'fp_' . $this->table . '__' . $key;
    }

    protected function createHintablePropsFromClassDoc(string $className): array
    {
        return array_map(function (HintablePropertyDef $hintableProp) {
            $hintableProp->key = $this->prefixKey($hintableProp->name);

            return $hintableProp;
        }, parent::createHintablePropsFromClassDoc($className));
    }

    protected function doInitialize(): void
    {
        if ($this->primaryKey === 'id') {
            $this->primaryKey = $this->prefixKey($this->primaryKey);
        }

        if ($this->titleKey === 'name') {
            $this->titleKey = $this->prefixKey($this->titleKey);
        }

        parent::doInitialize();
    }

    public function addField($name, $seed = []): Model\Field
    {
        $seed = \Phlex\Core\Factory::mergeSeeds($seed, [
            'actual' => $this->prefixKey($name, true),
        ]);

        return parent::addField($name, $seed);
    }
}

trait ModelLockTrait
{
    public function lock(): void
    {
        $this->getUserAction('add')->callback = function ($model) {
            return 'Form Submit! Data are not save in demo mode.';
        };
        $this->getUserAction('edit')->callback = function ($model) {
            return 'Form Submit! Data are not save in demo mode.';
        };

        $delete = $this->getUserAction('delete');
        $delete->confirmation = 'Please go ahead. Demo mode does not really delete data.';

        $delete->callback = function ($model) {
            return 'Only simulating delete when in demo mode.';
        };
    }
}

/**
 * @property string $name      @Phlex\Field()
 * @property string $sys_name  @Phlex\Field()
 * @property string $iso       @Phlex\Field()
 * @property string $iso3      @Phlex\Field()
 * @property string $numcode   @Phlex\Field()
 * @property string $phonecode @Phlex\Field()
 */
class Country extends ModelWithPrefixedFields
{
    public $table = 'country';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->addField($this->key()->name, ['actual' => 'atk_afp_country__nicename', 'required' => true, 'type' => 'string']);
        $this->addField($this->key()->sys_name, ['actual' => 'atk_afp_country__name', 'system' => true]);

        $this->addField($this->key()->iso, ['caption' => 'ISO', 'required' => true, 'type' => 'string', 'ui' => ['table' => ['sortable' => false]]]);
        $this->addField($this->key()->iso3, ['caption' => 'ISO3', 'required' => true, 'type' => 'string']);
        $this->addField($this->key()->numcode, ['caption' => 'ISO Numeric Code', 'type' => 'float', 'required' => true]);
        $this->addField($this->key()->phonecode, ['caption' => 'Phone Prefix', 'type' => 'float', 'required' => true]);

        $this->onHook(Model::HOOK_BEFORE_SAVE, function (self $model) {
            if (!$model->sys_name) {
                $model->sys_name = mb_strtoupper($model->name);
            }
        });
    }

    public function validate($intent = null): array
    {
        $errors = parent::validate($intent);

        if (mb_strlen($this->iso) !== 2) {
            $errors[$this->key()->iso] = 'Must be exactly 2 characters';
        }

        if (mb_strlen($this->iso3) !== 3) {
            $errors[$this->key()->iso3] = 'Must be exactly 3 characters';
        }

        // look if name is unique
        $c = $this->getModel()->tryLoadBy($this->key()->name, $this->name);
        if ($c->isLoaded() && $c->getId() !== $this->getId()) {
            $errors[$this->key()->name] = 'Country name must be unique';
        }

        return $errors;
    }
}

class CountryLock extends Country
{
    use ModelLockTrait;
    public $caption = 'Country';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->lock();
    }
}

/**
 * @property string    $project_name           @Phlex\Field()
 * @property string    $project_code           @Phlex\Field()
 * @property string    $description            @Phlex\Field()
 * @property string    $client_name            @Phlex\Field()
 * @property string    $client_address         @Phlex\Field()
 * @property Country   $client_country_iso     @Phlex\RefOne()
 * @property string    $client_country         @Phlex\Field()
 * @property bool      $is_commercial          @Phlex\Field()
 * @property string    $currency               @Phlex\Field()
 * @property string    $currency_symbol        @Phlex\Field()
 * @property float     $project_budget         @Phlex\Field()
 * @property float     $project_invoiced       @Phlex\Field()
 * @property float     $project_paid           @Phlex\Field()
 * @property float     $project_hour_cost      @Phlex\Field()
 * @property int       $project_hours_est      @Phlex\Field()
 * @property int       $project_hours_reported @Phlex\Field()
 * @property float     $project_expenses_est   @Phlex\Field()
 * @property float     $project_expenses       @Phlex\Field()
 * @property float     $project_mgmt_cost_pct  @Phlex\Field()
 * @property float     $project_qa_cost_pct    @Phlex\Field()
 * @property \DateTime $start_date             @Phlex\Field()
 * @property \DateTime $finish_date            @Phlex\Field()
 * @property \DateTime $finish_time            @Phlex\Field()
 * @property \DateTime $created                @Phlex\Field()
 * @property \DateTime $updated                @Phlex\Field()
 */
class Stat extends ModelWithPrefixedFields
{
    public $table = 'stat';
    public $title = 'Project Stat';

    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addField($this->key()->project_name, ['type' => 'string']);
        $this->addField($this->key()->project_code, ['type' => 'string']);
        $this->titleKey = $this->key()->project_name;
        $this->addField($this->key()->description, ['type' => 'text']);
        $this->addField($this->key()->client_name, ['type' => 'string']);
        $this->addField($this->key()->client_address, ['type' => 'text', 'ui' => ['form' => [Form\Control\Textarea::class, 'rows' => 4]]]);

        $this->hasOne($this->key()->client_country_iso, [
            'model' => [Country::class],
            'theirKey' => Country::hint()->key()->iso,
            'type' => 'string',
            'ui' => [
                'form' => [Form\Control\Line::class],
            ],
        ])
            ->addField($this->key()->client_country, Country::hint()->key()->name);

        $this->addField($this->key()->is_commercial, ['type' => 'boolean']);
        $this->addField($this->key()->currency, ['type' => ['enum', 'values' => ['EUR' => 'Euro', 'USD' => 'US Dollar', 'GBP' => 'Pound Sterling']]]);
        $this->addField($this->key()->currency_symbol, ['never_persist' => true]);
        $this->onHook(Model::HOOK_AFTER_LOAD, function (self $model) {
            /* implementation for "intl"
            $locale = 'en-UK';
            $fmt = new \NumberFormatter($locale . '@currency=' . $model->currency, NumberFormatter::CURRENCY);
            $model->currency_symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
             */

            $map = ['EUR' => '€', 'USD' => '$', 'GBP' => '£'];
            $model->currency_symbol = $map[$model->currency] ?? '?';
        });

        $this->addField($this->key()->project_budget, ['type' => 'money']);
        $this->addField($this->key()->project_invoiced, ['type' => 'money']);
        $this->addField($this->key()->project_paid, ['type' => 'money']);
        $this->addField($this->key()->project_hour_cost, ['type' => 'money']);

        $this->addField($this->key()->project_hours_est, ['type' => 'integer']);
        $this->addField($this->key()->project_hours_reported, ['type' => 'integer']);

        $this->addField($this->key()->project_expenses_est, ['type' => 'money']);
        $this->addField($this->key()->project_expenses, ['type' => 'money']);
        $this->addField($this->key()->project_mgmt_cost_pct, ['type' => 'float']);
        $this->addField($this->key()->project_qa_cost_pct, ['type' => 'float']);

        $this->addField($this->key()->start_date, ['type' => 'date']);
        $this->addField($this->key()->finish_date, ['type' => 'date']);
        $this->addField($this->key()->finish_time, ['type' => 'time']);

        $this->addField($this->key()->created, ['type' => 'datetime', 'ui' => ['form' => ['disabled' => true]]]);
        $this->addField($this->key()->updated, ['type' => 'datetime', 'ui' => ['form' => ['disabled' => true]]]);
    }
}

class Percent extends \Phlex\Data\Model\Field
{
    public $type = 'float'; // will need to be able to affect rendering and storage
}

/**
 * @property string $name             @Phlex\Field()
 * @property string $type             @Phlex\Field()
 * @property bool   $is_folder        @Phlex\Field()
 * @property File   $SubFolder        @Phlex\RefMany()
 * @property int    $count            @Phlex\Field()
 * @property Folder $parent_folder_id @Phlex\RefOne()
 */
class File extends ModelWithPrefixedFields
{
    public $table = 'file';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->addField($this->key()->name);

        $this->addField($this->key()->type, ['caption' => 'MIME Type']);
        $this->addField($this->key()->is_folder, ['type' => 'boolean']);

        $this->hasMany($this->key()->SubFolder, [
            'model' => [self::class],
            'theirKey' => self::hint()->key()->parent_folder_id,
        ])
            ->addField($this->key()->count, ['aggregate' => 'count', 'field' => $this->expr(['*'])]);

        $this->hasOne($this->key()->parent_folder_id, [
            'model' => [Folder::class],
        ])
            ->addTitle();
    }

    /**
     * Perform import from filesystem.
     */
    public function importFromFilesystem($path, $isSub = false)
    {
        if (!$isSub) {
            $path = __DIR__ . '/../' . $path;
        }

        $dir = new \DirectoryIterator($path);
        foreach ($dir as $fileinfo) {
            $name = $fileinfo->getFilename();

            if ($name === '.' || $name[0] === '.') {
                continue;
            }

            if ($name === 'src' || $name === 'demos' || $isSub) {
                $entity = $this->getModel(true)->createEntity();

                /*
                // Disabling saving file in db
                $m->save([
                    $this->key()->name => $fileinfo->getFilename(),
                    $this->key()->is_folder => $fileinfo->isDir(),
                    $this->key()->type => pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION),
                ]);
                */

                if ($fileinfo->isDir()) {
                    $entity->SubFolder->importFromFilesystem($dir->getPath() . '/' . $name, true);
                }
            }
        }
    }
}

class Folder extends File
{
    protected function doInitialize(): void
    {
        parent::doInitialize();

        $this->addCondition($this->key()->is_folder, true);
    }
}

class FileLock extends File
{
    use ModelLockTrait;
    public $caption = 'File';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->lock();
    }
}

/**
 * @property string      $name          @Phlex\Field()
 * @property SubCategory $SubCategories @Phlex\RefMany()
 * @property Product     $Products      @Phlex\RefMany()
 */
class Category extends ModelWithPrefixedFields
{
    public $table = 'product_category';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->addField($this->key()->name);

        $this->hasMany($this->key()->SubCategories, [
            'model' => [SubCategory::class],
            'theirKey' => SubCategory::hint()->key()->product_category_id,
        ]);
        $this->hasMany($this->key()->Products, [
            'model' => [Product::class],
            'theirKey' => Product::hint()->key()->product_category_id,
        ]);
    }
}

/**
 * @property string   $name                @Phlex\Field()
 * @property Category $product_category_id @Phlex\RefOne()
 * @property Product  $Products            @Phlex\RefMany()
 */
class SubCategory extends ModelWithPrefixedFields
{
    public $table = 'product_sub_category';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->addField($this->key()->name);

        $this->hasOne($this->key()->product_category_id, [
            'model' => [Category::class],
        ]);
        $this->hasMany($this->key()->Products, [
            'model' => [Product::class],
            'theirKey' => Product::hint()->key()->product_sub_category_id,
        ]);
    }
}

/**
 * @property string      $name                    @Phlex\Field()
 * @property string      $brand                   @Phlex\Field()
 * @property Category    $product_category_id     @Phlex\RefOne()
 * @property SubCategory $product_sub_category_id @Phlex\RefOne()
 */
class Product extends ModelWithPrefixedFields
{
    public $table = 'product';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->addField($this->key()->name);
        $this->addField($this->key()->brand);
        $this->hasOne($this->key()->product_category_id, [
            'model' => [Category::class],
        ])->addTitle();
        $this->hasOne($this->key()->product_sub_category_id, [
            'model' => [SubCategory::class],
        ])->addTitle();
    }
}

class ProductLock extends Product
{
    use ModelLockTrait;
    public $caption = 'Product';

    protected function doInitialize(): void
    {
        parent::doInitialize();
        $this->lock();
    }
}
