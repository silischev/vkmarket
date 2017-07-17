<?php

namespace Asil\VkMarket;

use Asil\VkMarket\Exception\VkException;
use Asil\VkMarket\Model\Album;
use Asil\VkMarket\Model\Photo;
use Asil\VkMarket\Model\Product;
use Asil\VkMarket\Service\AlbumService;
use Asil\VkMarket\Service\BaseService;
use Asil\VkMarket\Service\PhotoService;
use Asil\VkMarket\Service\ProductService;
use Asil\VkMarket\VkConnect;

class VkServiceDispatcher
{
    private $productService;
    private $photoService;
    private $albumService;

    public function __construct(VkConnect $connection)
    {
        new BaseService($connection);
        $this->productService = new ProductService($connection);
        $this->photoService = new PhotoService($connection);
        $this->albumService = new AlbumService($connection);
    }

    /**
     * Возвращает товар по id
     * @param int $id id товара в VK
     * @return object объект класса \Asil\VkMarket\Model\Product,
     * <br>boolean <b>false</b> если товар не найден
     * @throws VkException
     */
    public function getProductById ($id) {
        return $this->productService->getProductById($id);
    }

    /**
     * Возвращает список товаров в сообществе
     * По-умолчанию возвращает все товары
     * @param int $albumId id подборки
     * @param int $count количество возвращаемых товаров
     * @param int $offset смещение относительно первого найденного товара для выборки определенного подмножества
     * @return array массив объектов класса \Asil\VkMarket\Model\Product,
     * пустой массив если товары не обнаружены или подборка пуста
     * @throws VkException
     */
    public function getProductsInAlbum ($albumId = 0, $count = 10, $offset = 0) {
        return $this->productService->getProductsInAlbum($albumId, $count, $offset);
    }

    /**
     * Возвращает список категорий для товаров
     * @param int $count количество категорий
     * @param int offset дополнительные поля товара
     * @return array
     * @throws VkException
     */
    public function getCategories ($count, $offset = '') {
        return $this->productService->getCategories($count, $offset);
    }

    /**
     * Добавляет новый товар
     * @param Product $product
     * @param Photo $photo
     * @return int  id товара в VK
     * @throws VkException
     */
    public function addProduct(Product $product, Photo $photo)
    {
        return $this->productService->addProduct($product, $photo);
    }

    /**
     * Редактирует товар
     * @param Product $product
     * @param Photo $photo
     * @return boolean true
     * @throws VkException
     */
    public function editProduct(Product $product, Photo $photo = null)
    {
        return $this->productService->editProduct($product, $photo);
    }

    /**
     * Удаляет товар
     * @param integer $id
     * @return boolean true
     * @throws VkException
     */
    public function deleteProduct($id)
    {
        return $this->productService->deleteProduct($id);
    }

    /**
     * Деактивирует товар
     * @param integer $id
     * @return boolean true
     * @throws VkException
     */
    public function deactivateProduct($id)
    {
        $product = $this->productService->getProductById($id);
        if ($product) {
            $product->setAvailability(true);
            return $this->editProduct($product);
        }

        return false;
    }

    /**
     * Восстанавливает деактивированный товар
     * <br> После успешного выполнения возвращает <b>true</b>, если товар не найден
     * возвращает <b>false</b>
     * @param integer $id
     * @return boolean
     * @throws VkException
     */
    public function restoreProduct($id)
    {
        $product = $this->productService->getProductById($id);
        if ($product) {
            $product->setAvailability(false);
            return $this->editProduct($product);
        }

        return false;
    }

    /**
     * Добавляет новую подборку с товарами
     * @param Album $album
     * @param Photo $photo
     * @return boolean true
     * @throws VkException
     */
    public function addAlbum(Album $album, Photo $photo = null)
    {
        return $this->albumService->addAlbum($album, $this->photoService, $photo);
    }

    /**
     * Добавляет товар в подборки
     * @param array $albumIds массив id подборок
     * @param int $itemId id товара
     * @return int  1
     */
    public function addProductToAlbum(array $albumIds, $itemId)
    {
        return $this->albumService->addProductToAlbum($albumIds, $itemId);
    }

    /**
     * Возвращает список подборок
     * @param int $albumId id подборки
     * @return array массив объектов \Asil\VkMarket\Model\Album,
     * пустой массив если товары не обнаружены или подборка пуста
     * @throws VkException
     */
    public function getAlbums($count = 10, $offset = 0)
    {
        return $this->albumService->getAlbums($count, $offset);
    }

    /**
     * Возвращает подбороку товаров по id
     * @param int $albumId id подборки
     * @return object объект \Asil\VkMarket\Model\Album,
     * <br> <b>false</b> если подборка не найдена
     * @throws VkException
     */
    public function getAlbumById($albumId)
    {
        return $this->albumService->getAlbumById($albumId);
    }

    /**
     * Редактирует подборку с товарами
     * @param PhotoService $photoService
     * @param Photo $photo
     * @param int $albumId
     * @param string $title
     * @param boolean $mainAlbum
     * @throws VkException
     * @return int  1
     */
    public function editAlbum($albumId, Album $album, Photo $photo = null)
    {
        return $this->albumService->editAlbum($albumId, $album, $this->photoService, $photo);
    }

    /**
     * Удаляет подборку
     * @param int $albumId
     * @return boolean true
     * @throws VkException
     */
    public function deleteAlbum($albumId)
    {
        return $this->albumService->deleteAlbum($albumId);
    }


}