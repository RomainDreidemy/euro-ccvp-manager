<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ClientController extends AbstractController
{
    /**
     * @Route("/client", name="client")
     */
    public function index(EntityManagerInterface $em)
    {
        $clients = $em->getRepository(Client::class)->findAll();

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'Clients' => $clients
        ]);
    }

    /**
     * @Route("/client/{id}", name="clientShow")
     */
    public function show($id, EntityManagerInterface $em)
    {
        $client = $em->getRepository(Client::class)->find($id);

        return $this->render('client/index.html.twig', [
            'controller_name' => 'ClientController',
            'Client' => $client
        ]);
    }
}
