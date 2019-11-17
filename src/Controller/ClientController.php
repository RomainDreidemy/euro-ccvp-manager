<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Fournisseur;
use App\Form\ModifInfoType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ClientController extends AbstractController
{
    /**
     * @Route("/clients/list", name="client")
     * @isGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $em)
    {
        $clients = $em->getRepository(Client::class)->findAll();

        return $this->render('client/index.html.twig', [
            'Clients' => $clients
        ]);
    }

    /**
     * @Route("/clients/infos/{id}", name="clientShow")
     * @isGranted("ROLE_ADMIN")
     */
    public function show($id, EntityManagerInterface $em, ClientRepository $clientRepository, Request $request)
    {
        $client = $em->getRepository(Client::class)->find($id);
        $form = $this->createForm(ModifInfoType::class, $client);

        $form->handleRequest($request);


        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();

            $em->persist($data);
            $em->flush();

            $this->addFlash('success', "<b>" . $data->getName() . "</b> a bien été modifié dans la liste des clients");
            return $this->redirectToRoute('client');
        }

        return $this->render('client/show.html.twig', [
            'controller_name' => 'ClientController',
            'Client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/clients/new", name="clientNew")
     * @isGranted("ROLE_ADMIN")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ModifInfoType::class);

        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();

            $em->persist($data);
            $em->flush();


            $this->addFlash('success', "<b>" . $data->getName() . "</b> a bien été ajouté dans la liste des clients");
            return $this->redirectToRoute('client');
        }

        return $this->render('client/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
