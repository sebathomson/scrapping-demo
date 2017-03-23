<?php 
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php';
require 'bbdd.php';

use Goutte\Client;

function getCrawler($url, $method = 'GET')
{
    return ( new Client() )->request($method,  $url);
}

function getEspecialidades()
{
    $link    = Conectarse();
    $crawler = getCrawler('http://www.doctoralia.cl/medicos-especialistas');

    $crawler->filterXPath('//*[@id="resultados"]/ul/li/a')->each(function ($node) use ($link) {
        $especialidad_url    = $node->attr('href');
        $aux_id              = explode('-', $especialidad_url);
        $especialidad_id     = END( $aux_id );
        $especialidad_nombre = $node->text();

        $query_result = salutem_query($link,
            'INSERT INTO especialidades (codigo, nombre, url) VALUES (
            "'.$especialidad_id.'", 
            "'.$especialidad_nombre.'", 
            "'.$especialidad_url.'"
            );'
            );
    });
}

getEspecialidades();
?>