<?php

namespace VanEyk\MITM\Http\Controllers;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use VanEyk\MITM\Support\Path;

class AssetController
{
    public const ROUTE_NAME = 'assets';

    public function get(Request $request)
    {
        $path = str_replace('..', '', $request->get('path', ''));
        $absolutePath = Path::dist($path);

        if (!is_file($absolutePath)) {
            throw new NotFoundHttpException($path);
        }

        return response(file_get_contents($absolutePath))
            ->withHeaders([
                'content-type' => $this->inferContentType($absolutePath),
            ]);
    }

    private function inferContentType(string $absolutePath)
    {
        $file = new File($absolutePath);
        $extension = $file->getExtension();
        $contentType = [
            'js' => 'application/javascript',
            'css' => 'text/css',
        ][$extension] ?? 'text/html';

        return "$contentType; charset=utf-8";
    }
}
