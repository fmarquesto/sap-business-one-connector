<?php

namespace fmarquesto\SapBusinessOneConnector\Common;

use fmarquesto\SapBusinessOneConnector\Entities\IEntity;

trait SelectProperties
{
    protected array $selectProperties = [];

    public function selectProperties():array
    {
        return $this->selectProperties;
    }

    public function setSelect($property): IEntity
    {
        if(count($this->selectProperties) == 0)
            $this->selectProperties = $this->defaultSelect();
        $this->selectProperties[] = $property;
        return $this;
    }

    public function setMultipleSelect(array $properties): IEntity
    {
        foreach($properties as  $property)
            $this->setSelect($property);

        return $this;
    }
}
