<?php

namespace App\Controller;

use App\Entity\Cdr;
use App\Repository\CdrRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatController extends AbstractController
{
    #[Route('/stat', name: 'stat')]
    public function getStat(EntityManagerInterface $entityManager, Request $request): Response
    {
        $repository = $entityManager->getRepository(Cdr::class);
        $results = $repository->loadCustomerStat($request->request->all()['customerId']);

        return $this->json($results);
    }
}
