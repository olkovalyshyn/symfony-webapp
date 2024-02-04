<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'create_product')]
    public function createProduct(ManagerRegistry $doctrine, ValidatorInterface $validator): Response
    {
        $entityManager = $doctrine->getManager();
// $prod = $doctrine->getRepository(Product::class)->find(7);
// dd($prod);

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

        #[Route('/productall', name: 'product_showAll')]
    public function showAll(ProductRepository $productRepository):Response{
        $productsAll = $productRepository->findAll();
        if(!$productsAll){
            $this->createNotFoundException( 'No products found');
        }
        return $this->render('product/show.html.twig', ['product' => $productsAll]);
    }


    //++++++++++++++++ ВАРІАНТ 1 показу товару++++++++++++++++++++++
    // #[Route('/product/{id}', name: 'product_show')]
    // public function show(ManagerRegistry $doctrine, int $id):Response{
    //     $product = $doctrine->getRepository(Product::class)->find($id);
    //     if(!$product){
    //         this->createNotFoundException( 'No product found for id ' .$id);
    //     }
    //     return new Response('Check out this great product: ' .(string)$product);
    // }

    //+++++++++++++++++++++++++ ВАРІАНТ 2 показу товару++++++++++++++++++++++
    //     #[Route('/product/{id}', name: 'product_show')]
    // public function show(int $id, ProductRepository $productRepository):Response{
    //     $product = $productRepository->find($id);
    //     // dd("### product", $product);
    //     if(!$product){
    //         this->createNotFoundException( 'No product found for id ' .$id);
    //     }
    //     return $this->render('product/show.html.twig', ['product' => [$product]]);
    // }

        //+++++++++++++++++++++++++ ВАРІАНТ 3 показу товару++++++++++++++++++++++
        #[Route('/product/{id}', name: 'product_show')]
    public function show(Product $product):Response{
        return $this->render('product/show.html.twig', ['product' => [$product]]);
    }


            #[Route('/product/edit/{id}', name: 'product_edit')]
    public function edit(int $id, ManagerRegistry $doctrine):Response{
        $entityManager = $doctrine->getManager();
        $productForEdit = $entityManager->getRepository(Product::class)->find($id);

        $productForEdit->setName("New product name");
        $entityManager->flush();

        if(!$productForEdit){
            this->createNotFoundException( 'No product found for id ' .$id);
        }
        return new Response('Check out this great product: ' .(string)$productForEdit);
    }

            #[Route('/product/delete/{id}', name: 'product_delete')]
    public function delete(int $id, ManagerRegistry $doctrine):Response{
        $entityManager = $doctrine->getManager();
        $productForDelete = $entityManager->getRepository(Product::class)->find($id);
        
        $entityManager->remove($productForDelete);
        $entityManager->flush();

        if(!$productForDelete){
            $this->createNotFoundException( 'No product found for id ' .$id);
        }
        return new Response('Product delete. Name is ' .(string)$productForDelete);
    }

                #[Route('/product/findPrice/{price}', name: 'product_find_by_price')]
    public function findProductByPrice(int $price, ManagerRegistry $doctrine):Response{
        $entityManager = $doctrine->getManager();
        $productForFind = $entityManager->getRepository(Product::class)->findByPrice($price);

        return $this->render('product/show.html.twig', ['product' => $productForFind]);
    }


}
