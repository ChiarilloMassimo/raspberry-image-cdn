<?php

require_once __DIR__.'/../vendor/autoload.php';

use \Symfony\Component\HttpFoundation\Request;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use \Symfony\Component\HttpFoundation\JsonResponse;
use \Symfony\Component\HttpFoundation\Response;
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
        throw new NotFoundHttpException();
    }

    return new JsonResponse([
        'file' => sprintf('/%s',$image->getFile())
    ]);
});

$app->get('/{name}', function ($name) {
    $image = (new Image())->setName($name);

    if (!file_exists($image->getPath())) {
        throw new NotFoundHttpException;
    }

    $image = \Gregwar\Image\Image::open($image->getPath());

    $response = new Response(file_get_contents($image->png()));
    $response->headers->add(
        ['Content-Type' => 'image/png']
    );

    return $response;
});

$app->get('/{query}/{name}', function ($query, $name) {
    $image = (new Image())->setName($name);

    if (!file_exists($image->getPath())) {
        throw new NotFoundHttpException;
    }

    preg_match('#w_(\d+)#i', $query, $matchWidth);
    preg_match('#h_(\d+)#i', $query, $matchHeight);

    $image = \Gregwar\Image\Image::open($image->getPath());

    $width = $image->width();
    $height = $image->height();

    if ($matchWidth) {
        $width = end($matchWidth);
    }

    if ($matchHeight) {
        $height = end($matchHeight);
    }

    $image->zoomCrop($width, $height);

    $response = new Response(file_get_contents($image->png()));
    $response->headers->add(
        ['Content-Type' => sprintf('image/png')]
    );

    return $response;
});

$app->run();
