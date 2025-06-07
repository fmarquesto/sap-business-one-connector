# SAP Business One Connector

A lightweight PHP library for connecting and interacting with SAP Business One.

[![Latest Stable Version](https://img.shields.io/packagist/v/fmarquesto/sap-business-one-connector.svg)](https://packagist.org/packages/fmarquesto/sap-business-one-connector)
[![License](https://img.shields.io/packagist/l/fmarquesto/sap-business-one-connector.svg)](LICENSE)

[//]: # ([![Tests]&#40;https://github.com/fmarquesto/sap-business-one-connector/actions/workflows/tests.yml/badge.svg&#41;]&#40;https://github.com/fmarquesto/sap-business-one-connector/actions&#41;)

---

## 🚀 Features

- Connect to SAP Business One (SAP B1) using HTTP/REST
- Lightweight and easy to integrate
- Built on top of Guzzle for HTTP requests
- Environment-based configuration with Dotenv
- Designed with extensibility and testability in mind

---

## 🧰 Requirements

- PHP ^8.2
- SAP Business One with Service Layer API access
- Composer

---

## 📦 Installation

```bash
composer require fmarquesto/sap-business-one-connector
```

## 🛠 Usage

### 🧪 Basic Setup

```php
use fmarquesto\SapBusinessOneConnector\Client;

// Load environment variables
$client = new Client(); // Automatically loads from .env file in the project root

// Or specify configuration directly

$connectionData = new \fmarquesto\SapBusinessOneConnector\ConnectionData('https://your-sap-b1-service-layer-url', '50000', 'Database', 'UserName', 'Password');
$client = new Client(connectionData: $connectionData);
```

### Environment variables

```dotenv
SAP_HOST="https://xxxx"
SAP_PORT=50000
SAP_USER=user
SAP_PASS=pass
SAP_DB=DB
```

## 📥 GET Example: Fetch Items

```php
use fmarquesto\SapBusinessOneConnector\Client;
use fmarquesto\SapBusinessOneConnector\QueryBuilder;
use fmarquesto\SapBusinessOneConnector\Resources;

$client = new Client();
$response = $client->execute(
    (new QueryBuilder(Resources::Items, top:21))
    ->addSelect('ItemCode', 'ItemName')
);

$response->success(); // true
$response->hasNextPage(); // true 
$response->nextPage(); // Items?$select=ItemCode,%20ItemName&$top=1&$skip=20
$response->arrayBody(); // ['odata.metadata' => 'metadataurl', 'value' => [['ItemCode' => 'A00001', 'ItemName' => 'Item 1'], ...]], 'odata.nextLink' => 'nextlinkurl']
```

## 🧪 Testing

```bash
composer test
```

## 📜 License

MIT © Fede Marquesto
