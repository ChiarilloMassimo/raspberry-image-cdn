<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\BinaryFileResponse;
use RaspberryImageCdn\Model\Image;

$app = new Silex\Application();

$app->get('/upload', function (Request $request) {
    $url = $request->get('url');
    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
        throw new NotFoundHttpException();
    }

    $image = (new Image())->setUrl($url);
    $image->upload();

    if (!file_exists($image->getPath())) {
        return new BinaryFileResponse(__DIR__.'/placeholder.png');
    }

    return new JsonResponse([
        'file' => sprintf('/%s',$image->getFile())
    ]);
});

$app->get('/{name}', function ($name) {
    $image = (new Image())->setName($name);

    if (!file_exists($image->getPath())) {
        return new BinaryFileResponse(__DIR__.'/placeholder.png');
    }

    return new BinaryFileResponse($image->getPath());
});

$app->get('/{query}/{name}', function ($query, $name) {
    $cdnImage = (new Image())
        ->setName($name)
        ->setQuery($query);

    if (file_exists($cdnImage->getPath())) {
        return new BinaryFileResponse($cdnImage->getPath());
    }


    preg_match('#w_(\d+)#i', $query, $matchWidth);
    preg_match('#h_(\d+)#i', $query, $matchHeight);

    $image = \Gregwar\Image\Image::open(
        $cdnImage
            ->setQuery(null)
            ->getPath()
    );

    if (!$image->correct()) {
        return new BinaryFileResponse(__DIR__.'/placeholder.png');
    }
    
    $width = $image->width();
    $height = $image->height();

    if ($matchWidth) {
        $width = end($matchWidth);
    }

    if ($matchHeight) {
        $height = end($matchHeight);
    }

    $image->zoomCrop($width, $height);

    $image
        ->contrast(18)
        ->save(
        $cdnImage
            ->setQuery($query)
            ->getPath()
    );

    return new BinaryFileResponse($cdnImage->getPath());
});

$app->run();
