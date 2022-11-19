<?php

namespace fmarquesto\SapBusinessOneConnector;

use Dotenv\Dotenv;
use fmarquesto\SapBusinessOneConnector\Common\SelectProperties;
use GuzzleHttp\Client;

abstract class SAPConnector implements ISAPConnector
{
    use SelectProperties;

    private static string $baseVersionUrl = '/b1s/v1/';
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private string $database;

    protected Client $sapClient;
    private array $sessionLogin = [];
    protected array $selectProperties = [];
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

    abstract protected function endpoint(): string;
    abstract protected function key(): string;
    /**
     * This should be setted in order to avoid requesting unnecessary data
     * @return array
     */
    abstract protected function defaultSelect(): array;

    private function loadCredentialsFromEnvironment(): void
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
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

    protected function buildUrl(string $url, $get = true): string
    {
        if($get)
            return $this->getBaseUrl() . $url . "?" . $this->select() . $this->filter() . $this->top();

        return $this->getBaseUrl() . $url;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
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
     * @throws \GuzzleHttp\Exception\GuzzleException
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

    private function select(): string
    {
        $select = '$select=';
        if(count($this->selectProperties) > 0){
            $select .= implode(',',$this->selectProperties);
        }else{
            $select .= implode(',',$this->defaultSelect());
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

    public function getAll(): array
    {
        $this->all = true;
        return $this->request('GET', $this->buildUrl($this->endpoint()))['value']??[];
    }

    public function getOneByKey($key): array
    {
        return $this->request('GET', $this->buildUrl($this->endpoint() . "($key)"));
    }

    public function getAllByFilter(string $filter): array
    {
        $this->filter = rawurlencode($filter);
        $this->all = true;
        return $this->getAll();
    }

    public function getFirstByFilter(string $filter): array
    {
        $this->top = '&$top=1';
        return $this->getAllByFilter($filter);
    }

    public function create(array $data): array
    {
       return $this->request('POST',$this->buildUrl($this->endpoint(),false), $data);
    }

    public function update($key, array $data): void
    {
        $this->request('PATCH', $this->buildUrl($this->endpoint() . "($key)", false), $data);
    }

    public function delete($key): void
    {
        $this->request('DELETE',$this->buildUrl($this->endpoint() . "($key)", false));
    }

    public function updateByBatch(array $data): array
    {

    }
}
