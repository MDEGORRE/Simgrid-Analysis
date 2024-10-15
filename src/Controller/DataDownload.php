<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DataDownload extends AbstractController
{
    #[Route('/data-download-json', name:'download_file_json')]
    public function serveFileAsJson(Request $request) {
        $data = $request->query->all('data');
        file_put_contents('../src/Files/data.json', json_encode($data, JSON_PRETTY_PRINT));

        $response = new Response();
        $response->setContent(file_get_contents('../src/Files/data.json'));
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'data.json'
        );
        $response->headers->set('Content-Disposition', $disposition);

        unlink('../src/Files/data.json');
        return $response;
    }

    #[Route('/data-download-csv', name:'download_file_csv')]
    public function serveFileAsCSV(Request $request) {
        $data = $request->query->all('data');
        array_key_exists('consistency', $data[1]) ? array_unshift($data, ['Driver', 'Laps', 'Consistency']) : array_unshift($data, ['Driver', 'Gap']);
        $fileCSV = fopen('../src/Files/data.csv', 'w');
        foreach ($data as $fields) {
            fputcsv($fileCSV, $fields);
        }
        fclose($fileCSV);

        $response = new Response();
        $response->setContent(file_get_contents('../src/Files/data.csv'));
        $disposition = HeaderUtils::makeDisposition(
            HeaderUtils::DISPOSITION_ATTACHMENT,
            'data.csv'
        );
        $response->headers->set('Content-Disposition', $disposition);

        unlink('../src/Files/data.csv');
        return $response;
    }
}