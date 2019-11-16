<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Entity\Price;
use App\Entity\Product;
use App\Form\FormProductType;
use App\Form\ProductWithPricesType;
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
        $form = $this->createForm(FormProductType::class);
        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();
            $em->persist($data);
            $em->flush($data);
            $em->refresh($data);

//            Ajout des prix :
            for ($i=0; sizeof($request->request->get('fournisseurs')) > $i; $i++){
                $price = new Price();
                $price
                    ->setFournisseur(
                        $em->getRepository(Fournisseur::class)->find($_POST['fournisseurs'][$i])
                    )
                    ->setPublicPrice($request->request->get('public_price')[$i])
                    ->setNetPrice($request->request->get('public_price')[$i])
                    ->setReventePrice($request->request->get('public_price')[$i])
                    ->setProduct($data)
                    ;

                $em->persist($price);
                $em->flush();
            }

            return $this->redirectToRoute('productList');
        }

        return $this->render('product/new.html.twig', [
            'form' => $form->createView(),
            'fournisseurs' => $em->getRepository(Fournisseur::class)->findAll()
        ]);
    }

    /**
     * @Route("/products/{id}", name="productShow")
     * @isGranted("ROLE_ADMIN")
     */
    public function show($id, EntityManagerInterface $em, Request $request)
    {
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(FormProductType::class, $product);
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
