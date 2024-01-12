<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ApiProductsFormType;
use App\Repository\ProductsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiProductsController extends AbstractController
{
    #[Route('/new/product', name: 'app_new_product')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ApiProductsFormType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $product = $form->getData();
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_inside');
        }


        return $this->render('api_products/index.html.twig', [
            'product_form' => $form,
        ]);
    }
    #[Route('/api/products', name: 'app_products')]
    public function get_products(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();

        return $this->json($products);
    }
}
