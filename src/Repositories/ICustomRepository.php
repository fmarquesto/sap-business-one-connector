<?php

namespace fmarquesto\SapBusinessOneConnector\Repositories;

interface ICustomEntity
{
    function setEndpoint(string $endpoint):void;

    function getEndpoint():string;

    function setKey($key):void;

    /**
     * @return mixed
     */
    function getKey();

    function setDefaultSelect(array $defaultSelect):void;

    function getDefaultSelect():array;
}