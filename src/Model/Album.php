<?php

namespace Asil\VkMarket\Model;

/**
 * Class Album
 * Описывает подборку товаров в API ВК
 */

class Album
{
    private $title;
    private $photoId;
    private $mainAlbum;

    /**
     * Album constructor.
     *
     * @param $title    название подборки
     * @param $photo    id фото подборки
     * @param $mainAlbum    является ли подборка основной
     */
    public function __construct($title, $photoId = '', $mainAlbum = false)
    {
        $this->title = $title;
        $this->photoId = $photoId;
        $this->mainAlbum = $mainAlbum;
    }

    /**
     * @return название подборки
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param название $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $photoId
     */
    public function setPhotoId($photoId)
    {
        $this->photoId = $photoId;
    }

    /**
     * @return id подборки
     */
    public function getPhotoId()
    {
        return $this->photoId;
    }

    public function setMainAlbum()
    {
        $this->mainAlbum = true;
    }

    public function getMainAlbum()
    {
        return $this->mainAlbum;
    }


}