<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use fmarquesto\SapBusinessOneConnector\Repositories\IRepository;

class SAPManager
{
    protected string $filter = '';
    protected int $top = 0;
    private SAPBusinessOneConnector $SAPConnector;

    function __construct(SAPBusinessOneConnector $SAPConnector)
    {
        $this->SAPConnector = $SAPConnector;
    }

    private function buildUrl(IRepository $entity, $key = '', $get = true): string
    {
        $url =  $entity->endpoint();
        if($key!='')
            $url .="($key)";

        if($get)
            $url .= "?" . $this->select($entity) . $this->filter() . $this->top();

        return $url;
    }

    private function select(IRepository $entity): string
    {
        $select = '$select=';
        if(!empty($entity->selectProperties())){
            $select .= implode(',',$entity->selectProperties());
        }else{
            $select .= implode(',',$entity->defaultSelect());
        }
        return $select;
    }

    private function filter(): string
    {
        $filter = '';
        if($this->filter != '')
            $filter = '&$filter=' . $this->filter;
        $this->filter = '';
        return $filter;
    }

    private function top(): string
    {
        $top = $this->top>0? '&$top=' . $this->top : '';
        $this->top = 0;
        return $top;
    }

    public function setTop(int $top): void
    {
        $this->top = $top;
    }

    public function getAll(IRepository $entity): array
    {
        $this->SAPConnector->setPageSizeHeader(0);
        return $this->SAPConnector->execute('GET', $this->buildUrl($entity))['value']??[];
    }

    public function getOneByKey(IRepository $entity, $key): array
    {
        return $this->SAPConnector->execute('GET', $this->buildUrl($entity, $key));
    }

    public function getAllByFilter(IRepository $entity, string $filter): array
    {
        $this->filter = rawurlencode($filter);
        return $this->getAll($entity);
    }

    public function getFirstByFilter(IRepository $entity, string $filter): array
    {
        $this->setTop(1);
        return $this->getAllByFilter($entity, $filter);
    }

    public function create(IRepository $entity, array $data): array
    {
        return $this->SAPConnector->execute('POST',$this->buildUrl($entity,'', false), $data);
    }

    public function update(IRepository $entity, $key, array $data): void
    {
        $this->SAPConnector->execute('PATCH', $this->buildUrl($entity, $key, false), $data);
    }

    public function delete(IRepository $entity, $key): void
    {
        $this->SAPConnector->execute('DELETE',$this->buildUrl($entity, $key, false));
    }

    public function updateByBatch(IRepository $entity, array $data): array
    {
        return [];
    }
}
