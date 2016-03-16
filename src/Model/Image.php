<?php

namespace RaspberryImageCdn\Model;

class Image
{
    protected $url;

    protected $name;

    protected $query;

    public function getQuery()
    {
        return $this->query;
    }

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

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
        $name = pathinfo((!$this->name) ? $this->url : $this->name, PATHINFO_FILENAME);

        if ($this->getQuery()) {
            return sprintf('%s%s', $name, $this->getQuery());
        }

        if ($this->url) {
            return sprintf('%s-%s', md5($name), $name);
        }

        return $name;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    public function upload()
    {
        if (file_exists($this->getPath())) {
            return $this->getPath();
        }

        return \Gregwar\Image\Image::open($this->url)
            ->contrast(18)
            ->save($this->getPath());
    }

    public function getPath()
    {
        return sprintf(
            __DIR__.'/../../shared/images/%s',
            $this->getBasePath()
        );
    }

    public function getBasePath()
    {
        return sprintf(
            '%s/%s/%s',
            substr($this->getFileName(), 0, 3),
            substr($this->getFileName(), 3, 1),
            $this->getFileName()
        );
    }
}
