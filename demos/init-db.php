<?php

declare(strict_types=1);

namespace Phlex\Ui\Demos;

use Phlex\Data\Model;
use Phlex\Ui\Form;
use Mvorisek\Phlex\Hintable\Data\HintablePropertyDef;

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
    private function prefixFieldName(string $fieldName, bool $forActualName = false): string
    {
        if ($forActualName) {
            $fieldName = preg_replace('~^atk_fp_' . preg_quote($this->table, '~') . '__~', '', $fieldName);
        }

        return 'atk_' . ($forActualName ? 'a' : '') . 'fp_' . $this->table . '__' . $fieldName;
    }

    protected function createHintablePropsFromClassDoc(string $className): array
    {
        return array_map(function (HintablePropertyDef $hintableProp) {
            $hintableProp->fieldName = $this->prefixFieldName($hintableProp->name);

            return $hintableProp;
        }, parent::createHintablePropsFromClassDoc($className));
    }

    protected function init(): void
    {
        if ($this->id_field === 'id') {
            $this->id_field = $this->prefixFieldName($this->id_field);
        }

        if ($this->title_field === 'name') {
            $this->title_field = $this->prefixFieldName($this->title_field);
        }

        parent::init();
    }

    public function addField($name, $seed = []): \Phlex\Data\Model\Field
    {
        $seed = \Phlex\Core\Factory::mergeSeeds($seed, [
            'actual' => $this->prefixFieldName($name, true),
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

    protected function init(): void
    {
        parent::init();
        $this->addField($this->fieldName()->name, ['actual' => 'atk_afp_country__nicename', 'required' => true, 'type' => 'string']);
        $this->addField($this->fieldName()->sys_name, ['actual' => 'atk_afp_country__name', 'system' => true]);

        $this->addField($this->fieldName()->iso, ['caption' => 'ISO', 'required' => true, 'type' => 'string', 'ui' => ['table' => ['sortable' => false]]]);
        $this->addField($this->fieldName()->iso3, ['caption' => 'ISO3', 'required' => true, 'type' => 'string']);
        $this->addField($this->fieldName()->numcode, ['caption' => 'ISO Numeric Code', 'type' => 'number', 'required' => true]);
        $this->addField($this->fieldName()->phonecode, ['caption' => 'Phone Prefix', 'type' => 'number', 'required' => true]);

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
            $errors[$this->fieldName()->iso] = 'Must be exactly 2 characters';
        }

        if (mb_strlen($this->iso3) !== 3) {
            $errors[$this->fieldName()->iso3] = 'Must be exactly 3 characters';
        }

        // look if name is unique
        $c = $this->getModel()->tryLoadBy($this->fieldName()->name, $this->name);
        if ($c->loaded() && $c->getId() !== $this->getId()) {
            $errors[$this->fieldName()->name] = 'Country name must be unique';
        }

        return $errors;
    }
}

class CountryLock extends Country
{
    use ModelLockTrait;
    public $caption = 'Country';

    protected function init(): void
    {
        parent::init();
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

    protected function init(): void
    {
        parent::init();

        $this->addField($this->fieldName()->project_name, ['type' => 'string']);
        $this->addField($this->fieldName()->project_code, ['type' => 'string']);
        $this->title_field = $this->fieldName()->project_name;
        $this->addField($this->fieldName()->description, ['type' => 'text']);
        $this->addField($this->fieldName()->client_name, ['type' => 'string']);
        $this->addField($this->fieldName()->client_address, ['type' => 'text', 'ui' => ['form' => [Form\Control\Textarea::class, 'rows' => 4]]]);

        $this->hasOne($this->fieldName()->client_country_iso, [
            'model' => [Country::class],
            'their_field' => Country::hinting()->fieldName()->iso,
            'type' => 'string',
            'ui' => [
                'form' => [Form\Control\Line::class],
            ],
        ])
            ->addField($this->fieldName()->client_country, Country::hinting()->fieldName()->name);

        $this->addField($this->fieldName()->is_commercial, ['type' => 'boolean']);
        $this->addField($this->fieldName()->currency, ['values' => ['EUR' => 'Euro', 'USD' => 'US Dollar', 'GBP' => 'Pound Sterling']]);
        $this->addField($this->fieldName()->currency_symbol, ['never_persist' => true]);
        $this->onHook(Model::HOOK_AFTER_LOAD, function (self $model) {
            /* implementation for "intl"
            $locale = 'en-UK';
            $fmt = new \NumberFormatter($locale . '@currency=' . $model->currency, NumberFormatter::CURRENCY);
            $model->currency_symbol = $fmt->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
             */

            $map = ['EUR' => '???', 'USD' => '$', 'GBP' => '??'];
            $model->currency_symbol = $map[$model->currency] ?? '?';
        });

        $this->addField($this->fieldName()->project_budget, ['type' => 'money']);
        $this->addField($this->fieldName()->project_invoiced, ['type' => 'money']);
        $this->addField($this->fieldName()->project_paid, ['type' => 'money']);
        $this->addField($this->fieldName()->project_hour_cost, ['type' => 'money']);

        $this->addField($this->fieldName()->project_hours_est, ['type' => 'integer']);
        $this->addField($this->fieldName()->project_hours_reported, ['type' => 'integer']);

        $this->addField($this->fieldName()->project_expenses_est, ['type' => 'money']);
        $this->addField($this->fieldName()->project_expenses, ['type' => 'money']);
        $this->addField($this->fieldName()->project_mgmt_cost_pct, new Percent());
        $this->addField($this->fieldName()->project_qa_cost_pct, new Percent());

        $this->addField($this->fieldName()->start_date, ['type' => 'date']);
        $this->addField($this->fieldName()->finish_date, ['type' => 'date']);
        $this->addField($this->fieldName()->finish_time, ['type' => 'time']);

        $this->addField($this->fieldName()->created, ['type' => 'datetime', 'ui' => ['form' => ['disabled' => true]]]);
        $this->addField($this->fieldName()->updated, ['type' => 'datetime', 'ui' => ['form' => ['disabled' => true]]]);
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

    protected function init(): void
    {
        parent::init();
        $this->addField($this->fieldName()->name);

        $this->addField($this->fieldName()->type, ['caption' => 'MIME Type']);
        $this->addField($this->fieldName()->is_folder, ['type' => 'boolean']);

        $this->hasMany($this->fieldName()->SubFolder, [
            'model' => [self::class],
            'their_field' => self::hinting()->fieldName()->parent_folder_id,
        ])
            ->addField($this->fieldName()->count, ['aggregate' => 'count', 'field' => $this->persistence->expr($this, '*')]);

        $this->hasOne($this->fieldName()->parent_folder_id, [
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
                    $this->fieldName()->name => $fileinfo->getFilename(),
                    $this->fieldName()->is_folder => $fileinfo->isDir(),
                    $this->fieldName()->type => pathinfo($fileinfo->getFilename(), PATHINFO_EXTENSION),
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
    protected function init(): void
    {
        parent::init();

        $this->addCondition($this->fieldName()->is_folder, true);
    }
}

class FileLock extends File
{
    use ModelLockTrait;
    public $caption = 'File';

    protected function init(): void
    {
        parent::init();
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

    protected function init(): void
    {
        parent::init();
        $this->addField($this->fieldName()->name);

        $this->hasMany($this->fieldName()->SubCategories, [
            'model' => [SubCategory::class],
            'their_field' => SubCategory::hinting()->fieldName()->product_category_id,
        ]);
        $this->hasMany($this->fieldName()->Products, [
            'model' => [Product::class],
            'their_field' => Product::hinting()->fieldName()->product_category_id,
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

    protected function init(): void
    {
        parent::init();
        $this->addField($this->fieldName()->name);

        $this->hasOne($this->fieldName()->product_category_id, [
            'model' => [Category::class],
        ]);
        $this->hasMany($this->fieldName()->Products, [
            'model' => [Product::class],
            'their_field' => Product::hinting()->fieldName()->product_sub_category_id,
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

    protected function init(): void
    {
        parent::init();
        $this->addField($this->fieldName()->name);
        $this->addField($this->fieldName()->brand);
        $this->hasOne($this->fieldName()->product_category_id, [
            'model' => [Category::class],
        ])->addTitle();
        $this->hasOne($this->fieldName()->product_sub_category_id, [
            'model' => [SubCategory::class],
        ])->addTitle();
    }
}

class ProductLock extends Product
{
    use ModelLockTrait;
    public $caption = 'Product';

    protected function init(): void
    {
        parent::init();
        $this->lock();
    }
}
