<?php

namespace Makeable\QueryKit\Factory\Generators;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Makeable\QueryKit\Factory\ModelAttribute;
use Makeable\QueryKit\Factory\UnreachableValueException;

class AttributeReflection
{
    /**
     * @var ModelAttribute
     */
    protected $attribute;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var string
     */
    protected $name;

    /**
     * @param ModelAttribute $attribute
     */
    public function __construct(ModelAttribute $attribute)
    {
        $this->attribute = $attribute;
        $this->model = $attribute->getModel();
        $this->name = $attribute->getName();
    }

    /**
     * @return Generator
     * @throws UnreachableValueException
     */
    public function generator()
    {
        if ($this->attribute->hasDateConstraints()) {
            return new DateGenerator();
        }

        if ($generator = $this->resolveFromCasts()) {
            return $generator;
        }

        if ($generator = $this->resolveGeneratorFromValues([$this->gt, $this->gte, $this->lt, $this->lte])) {
            return $generator;
        }

        throw new UnreachableValueException("Could not recognize attribute format for '{$this->name}'");
    }

    protected function resolveFromCasts()
    {
        return array_get($this->model->getCasts(), $this->name);

        // TODO switch...
    }

    protected function resolveFromFactory()
    {
        try {
            $value = array_get(factory(get_class($this->model))->make(), $this->name);

            // TODO ...
        } catch (Exception $e) {
        }
    }

    /**
     * @param array $values
     * @return DateGenerator|NumberGenerator|StringGenerator|null
     */
    protected function resolveGeneratorFromValues(array $values)
    {
        $generator = null;

        foreach ($values as $value) {
            if ($generator = $this->resolveGeneratorFromValue($value)) {
                break;
            }
        }

        return $generator;
    }

    protected function resolveGeneratorFromValue($value)
    {
        if (is_numeric($value)) {
            return new NumberGenerator();
        }

        try {
            Carbon::parse($value);

            return new DateGenerator();
        } catch (Exception $e) {
        }

        if (is_string($value)) {
            return new StringGenerator();
        }
    }
}
