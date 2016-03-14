<?php

namespace RaspberryImageCdn\Model;

class Image
{
    protected $url;

    protected $name;

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getFileName()
    {
        if (!$this->name) {
            return pathinfo($this->url, PATHINFO_FILENAME);
        }

        return pathinfo($this->name, PATHINFO_FILENAME);
    }

    public function getFile()
    {
        return sprintf('%s', $this->getFileName());
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return bool|mixed|string
     * @throws \Exception
     */
    public function upload()
    {
        if (file_exists($this->getPath())) {
            return $this->getPath();
        }

        return \Gregwar\Image\Image::open($this->url)
            ->save($this->getPath());
    }

    public function getPath()
    {
        return sprintf(
            __DIR__.'/../../web/shared/uploads/%s',
            $this->getBasePath()
        );
    }

    public function getBasePath()
    {
        $hash = md5($this->getFileName());

        return sprintf(
            '%s/%s/%s',
            substr($hash, 0, 3),
            substr($hash, 3, 1),
            $this->getFile()
        );
    }
}