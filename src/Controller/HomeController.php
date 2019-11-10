<?php

namespace App\Controller;

use App\DataFixtures\TestFixtures;
use App\Entity\User;
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
//        $user = new User();
//        $password = $passwordEncoder->encodePassword($user, 'test');
//
//        $user->setEmail('dreidemyromain@gmail.com')
//            ->setRoles(['ROLE_ADMIN'])
//            ->setPassword($password);
//
//        $em->persist($user);
//        $em->flush();

        return $this->render('home/index.html.twig', []);
    }
}
