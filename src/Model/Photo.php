<?php

namespace Asil\VkMarket\Model;
use Asil\VkMarket\Exception\VkException;

/**
 * Class Photo
 * Описывает фотографию товара в API ВК
 */

class Photo
{
    private $mainPhotoParams = [];
    private $additionalPhotoParams = [];
    private $albumPhotoParams = '';

    /**
     * <b>Параметры $cropX, $cropY, $cropWidth доступно только для главной фотографии (фото для обложки)</b>
     *
     * @param string $path     Путь к картинке на сервере
     * @param boolean $mainPhoto является ли фотография обложкой товара (true — фотография для обложки, false — дополнительная фотография)
     * @param int $cropX        координата x для обрезки фотографии (верхний правый угол) - положительное число
     * @param int $cropY        координата y для обрезки фотографии (верхний правый угол) - положительное число
     * @param int $cropWidth    ширина фотографии после обрезки в px - положительное число, минимальное значение 400
     *
     * @throws VkException
     */
    public function createMainPhoto ($path, $cropX = 0, $cropY = 0, $cropWidth = 400)
    {
        if (!file_exists($path)) {
            throw new VkException('File ' . $path . ' not found');
        }

        if ($cropWidth < 400) {
            throw new VkException('Parameter cropWidth should be greater than or equal to 400');
        }

        $this->mainPhotoParams['path'] = $path;
        $this->mainPhotoParams['crop_x'] = $cropX;
        $this->mainPhotoParams['crop_y'] = $cropY;
        $this->mainPhotoParams['crop_width'] = $cropWidth;
    }

    /**
     * <b>Количество дополнительных фотографий должно быть не более 4</b>
     *
     * @param array $paths     Массив путей фото на сервере
     *
     * @throws VkException
     */
    public function createAdditionalPhoto (array $paths)
    {
        if (sizeof($paths) > 4) {
            throw new VkException('Number of additional photos should be no more than 4');
        }

        foreach ($paths as $path) {
            if (!file_exists($path)) {
                throw new VkException('File ' . $path . ' not found');
            }
        }

        $this->additionalPhotoParams = $paths;
    }

    /**
     * @param string $path     Путь к картинке на сервере
     *
     * @throws VkException
     */
    public function createAlbumPhoto ($path)
    {
        if (!file_exists($path)) {
            throw new VkException('File ' . $path . ' not found');
        }

        $this->albumPhotoParams = $path;
    }

    public function getMainPhotoParams()
    {
        return $this->mainPhotoParams;
    }

    public function getAdditionalPhotoParams()
    {
        return $this->additionalPhotoParams;
    }

    public function getAlbumPhotoParams()
    {
        return $this->albumPhotoParams;
    }

}