<?php

namespace App\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em)
    {
//        $client = new Client(1);
//
//        $client->setName('Romain test modif');
//
//        $em->persist($client);
//
//        $em->flush();

        $client = $em->getRepository(Client::class)->find(1);

        $client->setName('Je modifie le name');

        $em->persist($client);

        $em->flush();


        $clients = $em->getRepository(Client::class)->findAll();
        dd($clients);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
