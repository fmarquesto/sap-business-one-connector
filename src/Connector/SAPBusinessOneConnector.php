<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use Dotenv\Dotenv;
use fmarquesto\SapBusinessOneConnector\Repositories\IEntity;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SAPConnector implements ISAPConnector
{
    private static string $baseVersionUrl = '/b1s/v1/';
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private string $database;

    protected Client $sapClient;
    private array $sessionLogin = [];
    protected string $filter = '';
    protected string $top = '';
    protected bool $all = false;

    function __construct($connectionConfig = [])
    {
        if(empty($connectionConfig)){
            $this->loadCredentialsFromEnvironment();
        }else{
            $this->loadCredentialsFromArry($connectionConfig);
        }
        $this->sapClient = new Client(['verify' => false]);
    }

    private function loadCredentialsFromEnvironment(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
        $dotenv->required(['SAP_HOST', 'SAP_PORT', 'SAP_USER', 'SAP_PASS', 'SAP_DB']);
        $this->loadCredentialsFromArry($_ENV);
    }

    private function loadCredentialsFromArry(array $data)
    {
        $this->host = $data['SAP_HOST'];
        $this->port = $data['SAP_PORT'];
        $this->user = $data['SAP_USER'];
        $this->pass = $data['SAP_PASS'];
        $this->database = $data['SAP_DB'];
    }

    private function getCredentials() :array
    {
        return [
            'UserName'=>$this->user,
            'Password'=>$this->pass,
            'CompanyDB'=>$this->database,
            'Language'=>'23'
        ];
    }

    private function getBaseUrl(): string
    {
        return rtrim($this->host,'/') . ':' . $this->port . self::$baseVersionUrl;
    }

    private function getLoginUrl(): string
    {
        return $this->getBaseUrl() . 'Login';
    }

    private function getLogoutUrl(): string
    {
        return $this->getBaseUrl() . 'Logout';
    }

    protected function buildUrl(IEntity $entity, $key = '', $get = true): string
    {
        $url = $this->getBaseUrl() . $entity->endpoint();
        if($key!='')
            $url .="($key)";

        if($get)
            $url .= "?" . $this->select($entity) . $this->filter() . $this->top();

        return $url;
    }

    /**
     * @throws GuzzleException
     */
    public function login(): void
    {
        $res = $this->sapClient->post($this->getLoginUrl(), ['json' => $this->getCredentials()]);
        $this->sessionLogin['Cookie'] = implode(';',$res->getHeader('Set-Cookie'));
        if($this->all)
            $this->sessionLogin['Prefer'] = 'odata.maxpagesize=0';
        $this->all = false;
    }

    /**
     * @throws GuzzleException
     */
    public function logout(): void
    {
        $this->sapClient->get($this->getLogoutUrl(),['headers'=>$this->sessionLogin]);
    }

    protected function request(string $method, string $url, array $data = []): array
    {
        $this->login();
        $response = $this->sapClient->request($method, $url, ['json' => $data, 'headers' => $this->sessionLogin]);
        $this->logout();
        return json_decode($response->getBody()->__toString(), true)??[];
    }

    private function select(IEntity $entity): string
    {
        $select = '$select=';
        if(count($entity->selectProperties()) > 0){
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
        $top = $this->top;
        $this->top = '';
        return $top;
    }

    public function getAll(IEntity $entity): array
    {
        $this->all = true;
        return $this->request('GET', $this->buildUrl($entity))['value']??[];
    }

    public function getOneByKey(IEntity $entity, $key): array
    {
        return $this->request('GET', $this->buildUrl($entity, $key));
    }

    public function getAllByFilter(IEntity $entity, string $filter): array
    {
        $this->filter = rawurlencode($filter);
        $this->all = true;
        return $this->getAll($entity);
    }

    public function getFirstByFilter(IEntity $entity, string $filter): array
    {
        $this->top = '&$top=1';
        return $this->getAllByFilter($entity, $filter);
    }

    public function create(IEntity $entity, array $data): array
    {
       return $this->request('POST',$this->buildUrl($entity,false), $data);
    }

    public function update(IEntity $entity, $key, array $data): void
    {
        $this->request('PATCH', $this->buildUrl($entity, $key, false), $data);
    }

    public function delete(IEntity $entity, $key): void
    {
        $this->request('DELETE',$this->buildUrl($entity, $key, false));
    }

    public function updateByBatch(IEntity $entity, array $data): array
    {
        return [];
    }
}
