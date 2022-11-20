<?php

namespace fmarquesto\SapBusinessOneConnector\Common;

use fmarquesto\SapBusinessOneConnector\Repositories\IRepository;

trait SelectProperties
{
    protected array $selectProperties = [];

    public function selectProperties():array
    {
        return $this->selectProperties;
    }

    public function setSelect($property): IRepository
    {
        if(count($this->selectProperties) == 0)
            $this->selectProperties = $this->defaultSelect();
        $this->selectProperties[] = $property;
        return $this;
    }

    public function setMultipleSelect(array $properties): IRepository
    {
        foreach($properties as  $property)
            $this->setSelect($property);

        return $this;
    }
}
