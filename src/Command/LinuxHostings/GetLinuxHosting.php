<?php

namespace TomCan\CombellApi\Command\LinuxHostings;

use TomCan\CombellApi\Command\AbstractCommand;
use TomCan\CombellApi\Structure\LinuxHostings\LinuxHosting;

class GetLinuxHosting extends AbstractCommand
{

    /**
     * @var string
     */
    private $domainname;

    public function __construct($domainname)
    {
        parent::__construct("get", "/v2/linuxhostings");

        $this->domainname = $domainname;

    }

    public function prepare()
    {
        $this->setEndPoint("/v2/linuxhostings/" . $this->domainname);
    }

    /**
     * @return string
     */
    public function getDomainname()
    {
        return $this->domainname;
    }

    /**
     * @param string $domainname
     */
    public function setDomainname($domainname)
    {
        $this->domainname = $domainname;
    }

    public function processResponse($response)
    {

        $h = $response['body'];
        $response['response'] = new LinuxHosting(
            $h->domain_name,
            $h->servicepack_id,
            $h->max_webspace_size,
            $h->max_size,
            $h->webspace_usage,
            $h->actual_size,
            $h->ip,
            $h->ip_type,
            $h->ssh_host,
            $h->ftp_username,
            $h->ssh_username
        );

        return $response;

    }

}