<?php

namespace Belusic\LaravelEloquentNullification;


trait NullifyEmptyStringsTrait
{
    /**
     * A boolean to check if we already ran the nullification methods.
     *
     * @var bool
     */
    protected $nullificationRan = false;

    protected static $nullifyOnModelEvents = ['creating', 'updating', 'saving'];

    /**
     * Boot method for the trait watches model events for creating, updating, and saving.
     */
    public static function bootNullifyEmptyStrings()
    {
        foreach(static::$nullifyOnModelEvents as $event) {
            static::$event(function ($model) {
                $model->nullifyTheNullableAttributes();
            });
        }
    }

    /**
     *
     */
    protected function nullifyTheNullableAttributes()
    {
        if (false == $this->nullificationRan && is_array($this->nullable)) {
            foreach ($this->nullable as $attribute) {
                if (array_key_exists($attribute, $this->attributes)) {
                    $this->attributes[$attribute] = $this->setNullOnEmptyString($this->attributes[$attribute]);
                }
            }
            $this->nullificationRan = true;
        }
    }

    /**
     * @param $value
     * @return null
     */
    protected function setNullOnEmptyString($value)
    {
        return trim($value) !== '' ? $value : null;
    }
}