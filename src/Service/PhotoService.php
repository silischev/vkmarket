<?php

namespace Asil\VkMarket\Service;

use Asil\VkMarket\Exception\VkException;
use Asil\VkMarket\VkConnect;
use Asil\VkMarket\Model\Photo;

class PhotoService extends BaseService
{
    /**
     * @param Photo $photo
     *
     * @return int|string
     *
     * @throws VkException
     */
    public function uploadMainPhoto (Photo $photo)
    {
        $mainPhotoId = '';

        if (sizeof($photo->getMainPhotoParams())) {
            $uploadUrl = $this->getMarketUploadServer($photo, true);
            $path = $photo->getMainPhotoParams()['path'];
            $mainPhotoId = $this->savePicture($path, $uploadUrl, true);
        } else {
            throw new VkException('Main photo should be uploaded');
        }

        return $mainPhotoId;
    }

    /**
     * @param Photo $photo
     *
     * @return string
     *
     * @throws VkException
     */
    public function uploadAdditionalPhotos (Photo $photo)
    {
        $additionalPhotoIds = '';

        if (sizeof($photo->getAdditionalPhotoParams())) {
            foreach ($photo->getAdditionalPhotoParams() as $additionalPhoto) {
                $uploadUrl = $this->getMarketUploadServer($photo);
                $id = $this->savePicture($additionalPhoto, $uploadUrl);
                $additionalPhotoIds .= $id . ',';
            }
        }

        return $additionalPhotoIds;
    }

    /**
     * @param Photo $photo
     *
     * @return int
     *
     * @throws VkException
     */
    public function uploadAlbumPhoto(Photo $photo)
    {
        $uploadUrl = $this->getMarketAlbumUploadServer();
        $path = $photo->getAlbumPhotoParams();
        $albumPhotoId = $this->saveAlbumPicture($path, $uploadUrl);

        return $albumPhotoId;
    }

    /**
     * Возвращает адрес сервера для загрузки фотографии товара
     *
     * @param Photo $photo
     * @param boolean $mainPhoto
     *
     * @return string $url
     *
     * @throws VkException
     */
    private function getMarketUploadServer(Photo $photo , $mainPhoto = false)
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'group_id' => $this->connection->getGroupId(),
            'main_photo' => $mainPhoto,
            'v' => VkConnect::API_VERSION,
        ];

        if ($mainPhoto) {
            $mainPhotoParams = $photo->getMainPhotoParams();

            $cropParams = [
                'crop_x' => $mainPhotoParams['crop_x'],
                'crop_y' => $mainPhotoParams['crop_y'],
                'crop_width' => $mainPhotoParams['crop_width']
            ];

            $arr = array_merge($arr, $cropParams);
        }

        $url = $this->connection->getRequest('photos.getMarketUploadServer', $arr);

        return $url['response']['upload_url'];
    }

    /**
     * Сохраняет фотографии для товара
     *
     * @param string $path
     * @param string $uploadUrl    адрес сервера, полученный в методе getMarketUploadServer
     * @param boolean $mainPhoto
     *
     * @return int $id      идентификатор фотографии товара
     *
     * @throws VkException
     */
    private function savePicture($path, $uploadUrl, $mainPhoto = false)
    {
        $postParams = [
            'file' => curl_file_create($path, mime_content_type($path))
        ];

        $pictureParams = $this->connection->postRequest($uploadUrl, $postParams);

        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'group_id' => $this->connection->getGroupId(),
            'photo' => $pictureParams['photo'],
            'server' => $pictureParams['server'],
            'hash' => $pictureParams['hash'],
            'crop_data' => $mainPhoto ? $pictureParams['crop_data'] : '',
            'crop_hash' => $mainPhoto ? $pictureParams['crop_hash'] : '',
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('photos.saveMarketPhoto', $arr);

        return (int)$content['response'][0]['id'];
    }

    /**
     * Сохраняет фотографии для альбома
     *
     * @param string $path
     * @param string $uploadUrl    адрес сервера, полученный в методе getMarketUploadServer
     *
     * @return int $id      идентификатор фотографии товара
     *
     * @throws VkException
     */
    private function saveAlbumPicture($path, $uploadUrl)
    {
        $postParams = [
            'file' => curl_file_create($path, mime_content_type($path))
        ];

        $pictureParams = $this->connection->postRequest($uploadUrl, $postParams);

        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'group_id' => $this->connection->getGroupId(),
            'photo' => $pictureParams['photo'],
            'server' => $pictureParams['server'],
            'hash' => $pictureParams['hash'],
            'v' => VkConnect::API_VERSION,
        ];

        $content = $this->connection->getRequest('photos.saveMarketAlbumPhoto', $arr);

        return (int)$content['response'][0]['id'];
    }

    /**
     * Возвращает адрес сервера для загрузки фотографии товара
     *
     * @return string $url
     *
     * @throws VkException
     */
    private function getMarketAlbumUploadServer()
    {
        $arr = [
            'access_token' => $this->connection->getAccessToken(),
            'group_id' => $this->connection->getGroupId(),
            'v' => VkConnect::API_VERSION,
        ];

        $url = $this->connection->getRequest('photos.getMarketAlbumUploadServer', $arr);
        return $url['response']['upload_url'];
    }

    /**
     * Передача файла на сервер
     *
     * @param string $uploadUrl
     *
     * @return mixed
     *
     * @throws VkException
     */
    public function uploadPicture($uploadUrl)
    {
        $postParams = [
            'file' => curl_file_create($this->photo->getPath(), mime_content_type($this->photo->getPath()))
        ];

        return $this->connection->postRequest($uploadUrl, $postParams);
    }


}