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

    function __construct()
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
        $dotenv->required(['SAP_HOST', 'SAP_PORT', 'SAP_USER', 'SAP_PASS', 'SAP_DB']);
        $this->host = $_ENV['SAP_HOST'];
        $this->port = $_ENV['SAP_PORT'];
        $this->user = $_ENV['SAP_USER'];
        $this->pass = $_ENV['SAP_PASS'];
        $this->database = $_ENV['SAP_DB'];
        $this->sapClient = new Client(['verify' => false]);
    }

    abstract protected function endpoint(): string;
    abstract protected function key(): string;
    /**
     * This should be setted in order to avoid requesting unnecessary data
     * @return array
     */
    abstract protected function defaultSelect(): array;

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

    protected function buildUrl(string $url): string
    {
        return $this->getBaseUrl() . $url . "?" . $this->select() . $this->filter() . $this->top();
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function login(): void
    {
        $res = $this->sapClient->post($this->getLoginUrl(), ['json' => $this->getCredentials()]);
        $this->sessionLogin['Cookie'] = implode(';',$res->getHeader('Set-Cookie'));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function logout(): void
    {
        $this->sapClient->get($this->getLogoutUrl(),['headers'=>$this->sessionLogin]);
    }

    protected function get(string $url): array
    {
        $this->login();
        $response = $this->sapClient->get($this->buildUrl($url), ['headers'=>$this->sessionLogin]);
        $this->logout();
        return json_decode($response->getBody()->__toString(), true);
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
        return $filter;
    }

    private function top(): string
    {
        return $this->top;
    }

    public function getAll(): array
    {
        return $this->get($this->endpoint());
    }

    public function getOneByKey($key): array
    {
        return $this->get($this->endpoint() . "($key)");
    }

    public function getAllByFilter(string $filter): array
    {
        $this->filter = rawurlencode($filter);
        return $this->getAll();
    }

    public function getFirstByFilter(string $filter): array
    {
        $this->top = '&$top=1';
        return $this->getAllByFilter($filter);
    }

    public function create(array $data): array
    {
        // TODO: Implement create() method.
    }

    public function update($key, $data): void
    {
        // TODO: Implement update() method.
    }

    public function delete($key): void
    {
        // TODO: Implement delete() method.
    }
}
