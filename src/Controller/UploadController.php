<?php

namespace App\Controller;

use Psr\Log\LoggerInterface;
use App\Service\FileUploader;
use App\Libraries\GeoLocation;
use App\Repository\CdrRepository;
use App\Repository\GeoNamesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/doUpload', name: 'do-upload')]

    /**
     *  @param Request $request
     * @param string $uploadDir
     * @param FileUploader $uploader
     * @param LoggerInterface $logger
     * @param CdrRepository $cdrRepository
     * @return Response
     */
    public function index(Request $request,
                          string $uploadDir,
                          string $geo_api_key,
                          string $geo_api_url,
                          FileUploader $uploader,
                          LoggerInterface $logger,
                          CdrRepository $cdrRepository,
                          GeoLocation $geoLocation,
                          GeoNamesRepository $geoNamesRepository): Response
    {
        $token = $request->get("token");

        if (!$this->isCsrfTokenValid('upload', $token))
        {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed",  Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        $file = $request->files->get('myfile');

        if (empty($file))
        {
            return new Response("No file specified",
                Response::HTTP_UNPROCESSABLE_ENTITY, ['content-type' => 'text/plain']);
        }

        $filename = $file->getClientOriginalName();
        $uploader->upload($uploadDir, $file, $filename);

        $cdrRepository->importCsv("{$uploadDir}{$filename}", $geoLocation, $geoNamesRepository, $geo_api_url, $geo_api_key);

        return new Response("File uploaded",  Response::HTTP_OK,
            ['content-type' => 'text/plain']);
    }
}
