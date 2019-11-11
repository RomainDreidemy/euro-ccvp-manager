<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Product;
use App\Form\ModifInfoType;
use App\Form\ProductAddFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class ProductController extends AbstractController
{
    /**
     * @Route("/products/list", name="productList")
     * @isGranted("ROLE_ADMIN")
     */
    public function index(EntityManagerInterface $em)
    {

        $produits = $em->getRepository(Product::class)->findAll();
        return $this->render('product/index.html.twig', [
            'Products' => $produits,
        ]);
    }

    /**
     * @Route("/products/new", name="productNew")
     * @isGranted("ROLE_ADMIN")
     */
    public function new(EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ProductAddFormType::class);
        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('productList');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/{id}", name="productShow")
     * @isGranted("ROLE_ADMIN")
     */
    public function show($id, EntityManagerInterface $em, Request $request)
    {
        $form = $this->createForm(ProductAddFormType::class, $em->getRepository(Product::class)->find($id));
        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return $this->redirectToRoute('productList');
        }
        return $this->render('product/show.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
