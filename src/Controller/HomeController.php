<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Fournisseur;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder)
    {
        $products = $em->getRepository(Product::class)->findAll();
        $clients = $em->getRepository(Client::class)->findAll();
        $fournisseurs = $em->getRepository(Fournisseur::class)->findAll();


        return $this->render('home/index.html.twig', [
            'nb_products' => count($products),
            'nb_clients' => count($clients),
            'nb_fournisseurs' => count($fournisseurs)
        ]);
    }
}
