<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ApiProductsFormType;
use App\Form\SearchProductFormType;
use App\Repository\ProductsRepository;
use App\Service\Bdd_fetch;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiProductsController extends AbstractController
{
    #[Route('/new/product', name: 'app_new_product')]
    public function index(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        $product = new Products();
        $form = $this->createForm(ApiProductsFormType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $imageFile = $form->get('image')->getData();
            if($imageFile){
                $originalImageFile = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeImagename = $slugger->slug($originalImageFile);
                $newImagename = $safeImagename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try{
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newImagename
                    );
                } catch (FileException $e) {
                    return new JsonResponse($e);
                }
                $product->setImageName($newImagename);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('app_products_list');
        }


        return $this->render('api_products/index.html.twig', [
            'product_form' => $form->createView(),
        ]);
    }
    #[Route('/products/upload/test', name: 'app_products_upload_test')]
    public function testFile(Request $request)
    {
        // dd($request->files->get('imageFile'));
        /** @var UploadedFile $uploadedFile */
        $uploadedFile =$request->files->get('imageFile');
        $destination = $this->getParameter('kernel.project_dir').'/public/uploads/images';

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // Urlizer transform file name with spaces(' ') into file name with ('-');
        $newFilename = Urlizer::urlize($originalFilename).'-'.uniqid().'.'.$uploadedFile->guessExtension();
        dd($uploadedFile->move(
            $destination,
            $newFilename
        ));
    }
    #[Route('/products/list', name: 'app_products_list')]
    public function products_list(ProductsRepository $productsRepository)
    {
        $products = $productsRepository->findAll();

        return $this->render('products/list.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/show/products', name: 'app_show_products')]
    public function showProducts(Request $request): Response
    {

            $form = $this->createForm(SearchProductFormType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $ean = $form->getData()->getEan();
                return $this->redirectToRoute('app_search_product', [
                       'search_string' => $ean
                   ]);
            };
            return $this->render('products/show.html.twig', [
                'form' => $form->createView(),
            ]);
    }
    #[Route('/search/product/{search_string}', name: 'app_search_product')]
    public function searchProduct($search_string, Bdd_fetch $bdd_fetch): Response
    {
        if(is_numeric($search_string)){
            $req = 'numeric';
        } else {
            $req = 'not numeric';
        }
        $searchProduct = $bdd_fetch->fetch_bdd_information($search_string);
        return $this->render('products/search.html.twig',[
            'product' => $searchProduct,
            'req' => $req
        ]);
    }
    #[Route('/api/products', name: 'app_products')]
    public function get_products(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();

        return $this->json($products);
    }

    #[Route('/search/bdd', name: 'api_search_bdd', methods: 'POST')]
    public function search_product_bdd(Request $request, Bdd_fetch $bdd_fetch): Response
    {
        $req = $request->getContent();
        //dd($search_product);
        $search_product_json = json_decode($req);
        //dd($search_product_json->content);
        $search_product = $search_product_json->content;
        $result = $bdd_fetch->fetch_bdd_information($search_product);
        //dd($result);
        $result_json = json_encode($result);
        //dd($result_json);
        return $this->json($result_json);
    }
}
