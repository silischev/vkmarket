<?php

namespace Asil\VkMarket;

use Asil\VkMarket\Exception\VkException;

class VkHelper
{
    /**
     * @param array $content  массив с результатом ответа от API ВК
     *
     * @throws VkException
     */
    public static function checkError($content) {
        if (isset($content['error']['error_code']) && isset($content['error']['error_msg'])) {
            throw new VkException($content['error']['error_msg'], $content['error']['error_code']);
        } elseif (isset($content['error'])) {
            throw new VkException($content['error']);
        }
    }

}