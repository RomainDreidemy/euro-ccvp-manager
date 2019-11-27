<?php

namespace App\Controller;

use App\Entity\Documentation;
use App\Entity\Fournisseur;
use App\Entity\Price;
use App\Entity\Product;
use App\Form\AddDocumentationFormType;
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
                $fournisseur = $request->request->get('fournisseurs')[$i];
                if( $fournisseur != 0 && !in_array( $fournisseur, $fournisseurCheck ?? [])){
                    $fournisseurCheck[] =  $fournisseur;
                    $price = new Price();
                    $price
                        ->setFournisseur(
                            $em->getRepository(Fournisseur::class)->find($_POST['fournisseurs'][$i])
                        )
                        ->setPublicPrice($request->request->get('public_price')[$i])
                        ->setNetPrice($request->request->get('net_price')[$i])
                        ->setReventePrice($request->request->get('revente_price')[$i])
                        ->setProduct($data)
                    ;

                    $em->persist($price);
                    $em->flush();
                }

            }

            $this->addFlash('success', "<b>" . $data->getName() . "</b> a bien été ajouté dans la liste des produits");
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

        $formAddDocumentation = $this->createForm(AddDocumentationFormType::class);
        $formAddDocumentation->handleRequest($request);

        if ($formAddDocumentation->isSubmitted() && $formAddDocumentation->isValid()) {
            $file = $formAddDocumentation['documentation']->getData();
            $file->move(__DIR__ . '/../../public/assets/documentations', $file->getClientOriginalName());

            $documentation = (new Documentation())
                ->setName($file->getClientOriginalName())
                ->setProduct($product)
            ;

            $em->persist($documentation);

            $em->flush();

            return $this->redirectToRoute('productShow', ['id' => $product->getId()]);
        }

        return $this->render('product/show.html.twig', [
            'product' => $product,
            'formAddDocumentation' => $formAddDocumentation->createView()
        ]);
    }

    /**
     * @Route("/products/change/{id}", name="productChange")
     * @isGranted("ROLE_ADMIN")
     */
    public function change($id, EntityManagerInterface $em, Request $request)
    {
        $product = $em->getRepository(Product::class)->find($id);
        $form = $this->createForm(FormProductType::class, $product);
        $form->handleRequest($request);

        // Vérifier que le formulaire ait été envoyé et que son contenu est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération des données du formulaire
            $data = $form->getData();
            $em->persist($data);
            $em->flush($data);
            $em->refresh($data);

            foreach ($product->getPrices() as $pri){
                $product->removePrice($pri);
            }
//            Ajout des prix :
            for ($i=0; sizeof($request->request->get('fournisseurs')) > $i; $i++){
                $fournisseur = $request->request->get('fournisseurs')[$i];
                if( $fournisseur != 0 && !in_array( $fournisseur, $fournisseurCheck ?? [])){
                    $fournisseurCheck[] =  $fournisseur;
                    $price = new Price();
                    $price
                        ->setFournisseur(
                            $em->getRepository(Fournisseur::class)->find($_POST['fournisseurs'][$i])
                        )
                        ->setPublicPrice($request->request->get('public_price')[$i])
                        ->setNetPrice($request->request->get('net_price')[$i])
                        ->setReventePrice($request->request->get('revente_price')[$i])
                        ->setProduct($data)
                    ;

                    $em->persist($price);
                    $em->flush();
                }

            }
            $this->addFlash('success', "<b>" . $data->getName() . "</b> a bien été modifié dans la liste des produits");
            return $this->redirectToRoute('productList');
        }

        return $this->render('product/change.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
            'fournisseurs' => $em->getRepository(Fournisseur::class)->findAll()
        ]);
    }

    /**
     * @Route("/product/delete/{id}", name="productDelete")
     * @isGranted("ROLE_ADMIN")
     */
    public function delete($id, EntityManagerInterface $em)
    {
        $product = $em->getRepository(Product::class)->find($id);
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', "<b>" . $product->getName() . "</b> a bien été supprimer de la liste des produits");
        return $this->redirectToRoute('productList');
    }

    /**
     * @Route("/product/{id}/documentation/remove/{doc}", name="productRemoveDocumentation")
     * @isGranted("ROLE_ADMIN")
     */
    public function removeDocumentation(Product $product, Documentation $doc,EntityManagerInterface $em)
    {
        $product->removeDocumentation($doc);

        $em->persist($product);

        $em->flush();

        unlink(__DIR__ . '/../../public/assets/documentations/' . $doc->getName());

        $this->addFlash('success', 'La documentation n\'est plus liée au produit');

        return $this->redirectToRoute('productShow', ['id' => $product->getId()]);
    }
}
