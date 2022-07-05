<?php

namespace App\Controller;

use App\Repository\GeoNamesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GeoNamesController extends AbstractController
{
    #[Route('/uploadGeoNames' , 'name=UploadGeoNames')]
    public function index(GeoNamesRepository $geoNamesRepository): Response
    {
        $geoNamesRepository->importCsv('../countryInfo.csv');
        return new Response('country info imported');
    }
}
