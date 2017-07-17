<?php

namespace Asil\VkMarket\Service;

use Asil\VkMarket\VkConnect;

class BaseService
{
    /**
     * @var string BaseService connection
     */
    protected $connection;

    public function __construct(VkConnect $connection)
    {
        $this->connection = $connection;
    }
}