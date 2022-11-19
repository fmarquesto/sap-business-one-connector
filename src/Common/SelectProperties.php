<?php

namespace fmarquesto\SapBusinessOneConnector\Common;

use fmarquesto\SapBusinessOneConnector\ISAPConnector;

trait SelectProperties
{
    public function setSelect($property): ISAPConnector
    {
        if(count($this->selectProperties) == 0)
            $this->selectProperties = $this->defaultSelect();
        $this->selectProperties[] = $property;
        return $this;
    }

    public function setMultipleSelect(array $properties): ISAPConnector
    {
        foreach($properties as  $property)
            $this->setSelect($property);

        return $this;
    }
}
