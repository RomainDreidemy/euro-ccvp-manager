<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurInformationType;
use App\Form\FournisseurModificationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class FournisseurController extends AbstractController
{
    /**
     * @Route("/fournisseurs/list", name="fournisseurList")
     * @isGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $em)
    {
        $fournisseurs = $em->getRepository(Fournisseur::class)->findAll();

        return $this->render('fournisseur/index.html.twig', [
            'Fournisseurs' => $fournisseurs,
        ]);
    }

    /**
     * @Route("/fournisseurs/new", name="fournisseNew")
     * @isGranted("ROLE_ADMIN")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(FournisseurInformationType::class);
        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            $this->addFlash('success', "<b>" . $data->getName() . "</b> a bien été ajouté dans la liste des fournisseurs");
            return $this->redirectToRoute('fournisseurList');
        }

        return $this->render('fournisseur/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fournisseurs/{id}", name="fournisseurShow")
     * @isGranted("ROLE_ADMIN")
     */
    public function show($id, EntityManagerInterface $em, Request $request)
    {
        $fournisseur = $em->getRepository(Fournisseur::class)->find($id);
        $form = $this->createForm(FournisseurInformationType::class, $fournisseur);
        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            $this->addFlash('success', "<b>" . $data->getName() . "</b> a bien été modifié dans la liste des fournisseurs");
            return $this->redirectToRoute('fournisseurList');
        }

        return $this->render('fournisseur/show.html.twig', [
            'form' => $form->createView(),
            'prices' => $fournisseur->getPrices()
        ]);
    }
}
