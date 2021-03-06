<?php

namespace TomCan\CombellApi\Command\Accounts;

use TomCan\CombellApi\Command\PageableAbstractCommand;
use TomCan\CombellApi\Structure\Accounts\Account;

class ListAccounts extends PageableAbstractCommand
{
    private $assetType;
    private $identifier;

    public function __construct(string $assetType = '', string $identifier = '')
    {
        parent::__construct('get', '/v2/accounts');
        $this->setAssetType($assetType);
        $this->identifier = $identifier;
    }

    private function setAssetType(string $assetType): void
    {
        if ('' === $assetType) {
            $this->assetType = '';

            return;
        }

        if (!\in_array($assetType, ['domain', 'linux_hosting', 'mysql', 'dns', 'mailbox'], true)) {
            throw new \InvalidArgumentException('Invalid asset type specified');
        }

        $this->assetType = $assetType;
    }

    public function prepare(): void
    {
        parent::prepare();

        $this->appendQueryString('asset_type', $this->assetType);
        $this->appendQueryString('identifier', $this->identifier);
    }

    public function processResponse(array $response)
    {
        $accounts = [];
        foreach ($response['body'] as $account) {
            $accounts[] = new Account($account->id, $account->identifier, $account->servicepack_id);
        }

        return $accounts;
    }
}
