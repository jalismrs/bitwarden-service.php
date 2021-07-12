# Bitwarden-cli php service

## Requirements
`Bitwarden CLI` must be installed on host system

--> https://bitwarden.com/help/article/cli/

If necessary, set a custom bitwarden server url:

--> `bw config server <SERVER_URL>`

## Installation

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/jalismrs/bitwarden-service.php.git"
    }
  ],
  "require": {
    "jalismrs/bitwarden-php": "^1.0"
  }
}
```

## Usage

You must implement a `BitwardenServiceDelegate` to create an instance of this service.

then:
```php
$service = new BitwardenService(new MyBitwardenDelegate());
$items = $service->searchItems('web5902');

/** @var BitwardenItem $item */
$item = $items[0];
var_dump($item->getId());
var_dump($item->getName());
var_dump($item->getLogin()?->getUsername());
var_dump($item->getLogin()?->getPassword());
```