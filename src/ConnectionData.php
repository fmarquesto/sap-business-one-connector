<?php

namespace fmarquesto\SapBusinessOneConnector;

use JsonSerializable;

readonly class ConnectionData implements JsonSerializable
{
    public function __construct(public string $host, public string $port, public string $companyDB, public string $userName, public string $password, public int $language = 23)
    {

    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function loginData(): array
    {
        return [
            'CompanyDB' => $this->companyDB,
            'UserName' => $this->userName,
            'Password' => $this->password,
            'Language' => $this->language,
        ];
    }
}
