PHP Wrapper for VK market API
==============================
Библиотека реализует работу с VK market API (https://vk.com/dev/market).

Перед использованием библиотеки необходимо зарегистрировать свое приложение
для работы с API ВКонтакте (подробнее здесь - https://vk.com/dev/first_guide).
Также требуется получить ключ доступа - https://vk.com/dev/access_token.

Установка
==============================
Через composer:
```
composer require asil/vkmarket
```

Примеры использования
============================
1. Создаём соединение с *VkConnect* передав в него предварительно полученный ***access_token***,
затем инициализируем объект класса *VkServiceDispatcher* и вызываем у него необходимые методы для работы с товарами
```php
<?php 
use Asil\VkMarket\VkConnect;
use Asil\VkMarket\VkServiceDispatcher;
  
$accessToken = 'dbe359235b784';
$ownerId = 741281; // идентификатор владельца группы
$groupId = 101234567; // идентификатор группы
  
$connect = new VkConnect($accessToken, $groupId, $ownerId);
$vkService = new VkServiceDispatcher($connect);
```

2. Добавление товара в группу
```php
<?php 
use Asil\VkMarket\Model\Photo;
use Asil\VkMarket\Model\Product;
  
$product = new Product('Товар 1', 'Описание товара...', 3, 212);
$photo = new Photo();
  
$photo->createMainPhoto('/img/1.jpg');
$photo->createAdditionalPhoto([
    '/img/1_1.jpg',
    '/img/1_2.jpg'
]);
  
$id = $vkService->addProduct($product, $photo);
```
3. Редактирование товара
```php
<?php 
use Asil\VkMarket\Model\Photo;
use Asil\VkMarket\Model\Product;
  
$product = $vkService->getProductById(1432679);
$product->setName('Тестовый товар 2');
  
$photo = new Photo();
$photo->createAdditionalPhoto([
    '/img/6.jpg'
]);
  
$vkService->editProduct($product, $photo);
```
4. Деактивация товара
```php
<?php 
$vkService->deactivateProduct(1432679);
```

5. Восстановление деактивированного товара
```php
<?php 
$vkService->restoreProduct(1432679);
```

6. Удаление товара
```php
<?php 
$vkService->deleteProduct(1432679);
```

7. Создание подборки товаров
```php
<?php
use Asil\VkMarket\Model\Photo;
use Asil\VkMarket\Model\Album;
  
$album = new Album('Альбом 1');
  
$photo = new Photo();
$photo->createAlbumPhoto('/img/album_1.jpg');
  
$vkService->addAlbum($album, $photo);
```

8. Добавление товара в подборку
```php
<?php
$albumIds = [6, 1];
$vkItemId = 1432679;
$vkService->addProductToAlbum($albumIds, $vkItemId);
```

Список методов класса *VkServiceDispatcher*
=============================================
* ```getCategories($count, $offset = '')``` - возвращает список категорий для товаров
* ```addProduct(Product $product, Photo $photo)``` - добавляет новый товар
* ```editProduct(Product $product, Photo $photo = null)``` - редактирует товар
* ```getProductById($id)``` - возвращает товар по id
* ```deleteProduct($id)``` - удаляет товар
* ```deactivateProduct($id)``` - деактивирует товар
* ```restoreProduct($id)``` - восстанавливает деактивированный товар
* ```addAlbum(Album $album, Photo $photo = null)``` - добавляет новую подборку с товарами
* ```getProductsInAlbum($albumId = 0, $count = 10, $offset = 0)``` - возвращает список товаров в подборке  
(по-умолчанию возвращает все товары)
* ```addProductToAlbum(array $albumIds, $itemId)``` - добавляет товар в подборки
* ```getAlbums($count = 10, $offset = 0)``` - возвращает список подборок
* ```getAlbumById($albumId)``` - возвращает подбороку товаров по id
* ```editAlbum($albumId, Album $album, Photo $photo = null)``` - редактирует подборку с товарами
* ```deleteAlbum($albumId)``` - удаляет подборку

Лицензия
=======================================
MIT (пожалуйста прочтите файл LICENSE).