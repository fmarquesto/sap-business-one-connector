<?php

namespace fmarquesto\SapBusinessOneConnector\Entities;

use fmarquesto\SapBusinessOneConnector\Connector\ISAPConnector;
use fmarquesto\SapBusinessOneConnector\Exceptions\NoDefaultSelectException;
use fmarquesto\SapBusinessOneConnector\Exceptions\NoEndpointException;
use fmarquesto\SapBusinessOneConnector\Exceptions\NoKeyException;

class CustomEntity extends Entity implements ICustomEntity
{
    private string $endpoint;
    private $key;
    private array $defaultSelect;

    /**
     * @throws NoDefaultSelectException
     * @throws NoEndpointException
     * @throws NoKeyException
     */
    public function __construct(ISAPConnector $connector, string $endpoint, $key, array $defaultSelect)
    {
        $this->setEndpoint($endpoint);
        $this->setKey($key);
        $this->setDefaultSelect($defaultSelect);
        parent::__construct($connector);
    }

    function endpoint(): string
    {
        return $this->getEndpoint();
    }

    function key(): string
    {
        return $this->getKey();
    }

    function defaultSelect(): array
    {
        return $this->getDefaultSelect();
    }

    /**
     * @throws NoEndpointException
     */
    function setEndpoint(string $endpoint): void
    {
        if($endpoint == '')
            throw new NoEndpointException('You must defined an endpoint to use a custom entity');
        $this->endpoint = $endpoint;
    }

    function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @throws NoKeyException
     */
    function setKey($key): void
    {
        if($key == '')
            throw new NoKeyException('You must defined a key to use a custom entity');
        $this->key = $key;
    }

    function getKey()
    {
        return $this->key;
    }

    /**
     * @throws NoDefaultSelectException
     */
    function setDefaultSelect(array $defaultSelect): void
    {
        if(empty($defaultSelect))
            throw new NoDefaultSelectException("You must define a default select to use a custom entity");
        $this->defaultSelect = $defaultSelect;
    }

    function getDefaultSelect(): array
    {
        return $this->defaultSelect;
    }
}
