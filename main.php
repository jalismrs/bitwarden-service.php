<?php

require_once 'vendor/autoload.php';

use Jalismrs\Bitwarden\BitwardenService;
use Jalismrs\Bitwarden\BitwardenServiceDelegate;
use Jalismrs\Bitwarden\Model\BitwardenItem;

class MyBitwardenDelegate implements BitwardenServiceDelegate
{
    private ?string $session = null;

    public function getOrganizationId(): string
    {
        return '70608c07-8091-4d85-ae8d-18b90096b390';
    }

    public function getUserEmail(): string
    {
        return 'si@jalis.fr';
    }

    public function getUserPassword(): string
    {
        return '1form@tik_Masterp@$$wd';
    }

    public function storeSession(string $session): void
    {
        var_dump($session);
        $this->session = $session;
    }

    public function restoreSession(): ?string
    {
        return '/NIlg9/whOSK9EdUGtIHaSgYpC8To24/f1/Xr14akYXvPfobUHuo5xNwaHf3NAFqUUfkYiSSaS75/vAV8CqPKw==';
        return $this->session;
    }
}

$service = new BitwardenService(new MyBitwardenDelegate());
$items = $service->searchItems('web5902');

/** @var BitwardenItem $item */
$item = $items[0];
var_dump($item->getId());
var_dump($item->getName());
var_dump($item->getLogin()?->getUsername());
var_dump($item->getLogin()?->getPassword());
