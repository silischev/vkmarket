<?php

namespace Asil\VkMarket\Service;

use Asil\VkMarket\Model\Album;
use Asil\VkMarket\VkConnect;
use Asil\VkMarket\Model\Photo;

class AlbumService extends BaseService
{
    public function addAlbum(Album $album, PhotoService $photoService = null, Photo $photo = null)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'owner_id' => '-' . $this->connection->getGroupId(),
            'title' => $album->getTitle(),
            'photo_id' => ($photo && strlen($photo->getAlbumPhotoParams()) > 0 && $photoService) ? $photoService->uploadAlbumPhoto($photo) : '',
            'main_album' => $album->getMainAlbum(),
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('market.addAlbum', $arr);
        return (boolean)$content['response'];
    }

    public function editAlbum($albumId, Album $album, PhotoService $photoService = null, Photo $photo = null)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'owner_id' => '-' . $this->connection->getGroupId(),
            'album_id' => $albumId,
            'title' => $album->getTitle(),
            'photo_id' => $photo && $photoService ? $photoService->uploadAlbumPhoto($photo) : $album->getPhotoId(),
            'main_album' => $album->getMainAlbum(),
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('market.editAlbum', $arr);

        return (boolean)$content['response'];
    }

    public function addProductToAlbum(array $albumIds, $itemId)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'owner_id' => '-' . $this->connection->getGroupId(),
            'item_id' => $itemId,
            'album_ids' => $albumIds,
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('market.addToAlbum', $arr);

        return (boolean)$content['response'];
    }

    public function getAlbumById($albumId)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'owner_id' => '-' . $this->connection->getGroupId(),
            'album_ids' => [$albumId],
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('market.getAlbumById', $arr);

        $album = false;

        if (sizeof($content['response']['items'])) {
            if ($content['response']['items'][0]['id'] > 0) {
                $album = new Album($content['response']['items'][0]['title'], $content['response']['items'][0]['photo']['id']);
            }
        }

        return $album;
    }

    public function deleteAlbum($albumId)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'owner_id' => '-' . $this->connection->getGroupId(),
            'album_id' => $albumId,
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('market.deleteAlbum', $arr);

        return (boolean)$content['response'];
    }

    public function getAlbums($count, $offset)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'owner_id' => '-' . $this->connection->getGroupId(),
            'count' => $count,
            'offset' => $offset,
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('market.getAlbums', $arr);

        $albumArr = [];

        if (sizeof($content['response']['items'])) {
            foreach ($content['response']['items'] as $item) {
                if ($item['id'] > 0) {
                    $album = new Album($item['title'], $item['photo']['id']);
                    $albumArr[] = $album;
                }
            }
        }


        return $albumArr;
    }


}