<?php

namespace TomCan\CombellApi\Dns;

use TomCan\CombellApi\Common\AbstractCommand;

class CreateCnameRecord extends AbstractCommand
{

    private $domainname;
    private $hostname;
    private $target;
    private $ttl;

    public function __construct($domainname, $hostname, $target, $ttl)
    {
        parent::__construct("post", "/v2/dns/{domainname}/cnamerecords");

        $this->setDomainname($domainname);
        $this->setHostname($hostname);
        $this->setTarget($target);
        $this->setTtl($ttl);
    }

    public function prepare()
    {

        $this->setEndPoint("/v2/dns/".$this->domainname."/cnamerecords");

        $obj = new \stdClass();
        $obj->record = $this->hostname;
        $obj->ip_address = $this->target;
        $obj->ttl = $this->ttl;

        $this->setBody(
            json_encode($obj)
        );

    }

    /**
     * @return mixed
     */
    public function getDomainname()
    {
        return $this->domainname;
    }

    /**
     * @param mixed $domainname
     */
    public function setDomainname($domainname)
    {
        $this->domainname = $domainname;
    }

    /**
     * @return mixed
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @param mixed $hostname
     */
    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    /**
     * @return mixed
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param mixed $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return mixed
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * @param mixed $ttl
     */
    public function setTtl($ttl)
    {
        $ttl = (int)$ttl;
        if ($ttl < 60 || $ttl > 86400) {
            throw new \RangeException("Invalid value for ttl");
        }
        $this->ttl = $ttl;
    }

}