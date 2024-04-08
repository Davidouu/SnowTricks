<?php

namespace App\Service;

class VideosService
{
    public function getEmbedLink(string $url): string
    {

        // On récupère la position du src
        $pos = strpos($url, 'src="');

        // On récupère la position du premier guillemet après le src
        $pos2 = strpos($url, '"', $pos + 5);

        // On récupère la longueur de la chaine
        $length = $pos2 - $pos;

        // On récupère le lien
        $embedUrl = substr($url, $pos + 5, $length - 5);

        return $embedUrl;
    }
}
