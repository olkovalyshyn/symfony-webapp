<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Product;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'create_product')]
    public function createProduct(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();

        $product = new Product();
        $product->setName('Chair');
        $product->setPrice(1200);
        $product->setDescription('Comfortable');

        $entityManager->persist($product);
        $entityManager->flush();

$errors = $validator->validate($product);
if(count($errors) > 0){
    return new Response((string) $errors, 400);
}

        return new Response('Saved new product with id '.$product->getId());
    }
}
