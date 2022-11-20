<?php

namespace fmarquesto\SapBusinessOneConnector\Connector;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SAPBusinessOneConnector implements ISAPBusinessOneConnector
{
    private static string $baseVersionUrl = '/b1s/v1/';
    private string $host;
    private int $port;
    private string $user;
    private string $pass;
    private string $database;

    protected Client $sapClient;
    private array $sessionLogin = [];

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

    /**
     * @throws GuzzleException
     */
    private function login(): void
    {
        $res = $this->sapClient->post($this->getLoginUrl(), ['json' => $this->getCredentials()]);
        $this->sessionLogin['Cookie'] = implode(';',$res->getHeader('Set-Cookie'));
    }

    /**
     * @throws GuzzleException
     */
    private function logout(): void
    {
        $this->sapClient->get($this->getLogoutUrl(),['headers'=>$this->sessionLogin]);
    }

    public function execute(string $method, string $url, array $data = []): array
    {
        $this->login();
        $response = $this->sapClient->request($method, $this->getBaseUrl() . $url, ['json' => $data, 'headers' => $this->sessionLogin]);
        $this->logout();
        $this->sessionLogin = [];
        return json_decode($response->getBody()->__toString(), true)??[];
    }

    /**
     * @return void
     */
    public function setGetAllHeader(bool $all): void
    {
        if ($all)
            $this->sessionLogin['Prefer'] = 'odata.maxpagesize=0';
    }
}
