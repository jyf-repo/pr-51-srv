<?php

namespace App\Controller;

use App\Entity\Products;
use App\Form\ApiProductsFormType;
use App\Form\SearchProductFormType;
use App\Repository\ProductsRepository;
use App\Service\Bdd_fetch;
use App\Service\LgoServiceJson;
use App\unmigrate_entity\Belreponse;
use App\unmigrate_entity\MedipimProducts;
use App\unmigrate_entity\Rootrequest;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Sluggable\Util\Urlizer;
use Medipim\Api\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\param;

class ApiProductsController extends AbstractController
{
    /* Products LGP */
    #[Route('/lgp/api/products', name: 'api_lgp_products')]
    public function get_LGP_products(LgoServiceJson $lgoServiceJson, SerializerInterface $serializer)
    {
        $req = $lgoServiceJson->getProduct(); //string
        //dd($req);
        // --------------------Methode recuperation d'un tableau de tableaux
        $reqObject =  $serializer->deserialize($req, Rootrequest::class, 'json');//Object
        //dd($reqObject);
        //dd($reqObject->getBelreponse());
        //dd($reqObject->getBelreponse()->getSstock());
        //dd($reqObject->getBelreponse()->getSstock()->getProduit());
        $products = $reqObject->getBelreponse()->getSstock()->getProduit();

        //----------------------Methode recuperation d'un tableau de json
        //dd(json_decode($req));
        //$reqJson = json_decode($req); //json
        //dd($reqJson->belreponse->sstock->produit);
        //$products = $reqJson->belreponse->sstock->produit;

        return $this->render('/products/lgp.html.twig', [
            'products' => $products
        ]);
    }

    #[Route('/lgp/api/send/sale', name:'api_lgp_sale')]
    public function send_sale(LgoServiceJson $lgoServiceJson, SerializerInterface $serializer, DecoderInterface $decoder, EncoderInterface $encoder)
    {
        // from array to xml invoice
        $array_num_vente = 5555555562;
        $array_client = [
            "client" => [
                "@client_id" => 501,
                "nom" =>"test",
                "prenom" => "web",
                "adresse_facturation"=> [
                    "rue1"=>"rue du test",
                    "codepostal"=>"54000",
                    "ville"=>"Nancy",
                    "pays"=>"FRANCE",
                    "destinataire"=>"![CDATA[GPB TEST]]"
                ],
            "sexe"=>"F"
            ]
        ];
        //dd(json_encode($array_client));
        $array_date = [
            "date_vente" => "2018-03-13T14:03:47",
        ];
        $array_port = [
            "montant_port_ht"=>"0",
            "tauxtva_port"=>"20",
            "total_ttc"=>"0",
            "exoneration_tva"=>"0"
        ];
        $array_lignes_vente = [
            "lignevente"=>[
                [
                    "@numero_lignevente"=>1,
                    "codeproduit"=>"3401060160463",
                    "designation_produit"=>"Tielle Lite Pans hydrocel adh10x30cm B/10 ",
                    "quantite"=>"2",
                    "prix_brut"=>"56.84",
                    "remise"=>"20",
                    "prix_net"=>"45.44",
                    "tauxtva"=>"20"
                ],
                [
                    "@numero_lignevente"=>2,
                    "codeproduit"=>"3401560504477",
                    "designation_produit"=>"LACTIBIANE REF GELU 10",
                    "quantite"=>"2",
                    "prix_brut"=>"11.40",
                    "remise"=>"0",
                    "prix_net"=>"11.40",
                    "tauxtva"=>"20"
                ]
            ]
        ];

        $array_init = [
            "@num_pharma" =>intval($this->getParameter('login')),
            "@numero_vente" => $array_num_vente
        ];
        $array_vente = array_merge($array_init, $array_client, $array_date, $array_port, $array_lignes_vente);

        //dd($array_vente);
        $xml_vente = $encoder->encode($array_vente, 'xml', [
            'xml_format_output' => true,
            'xml_root_node_name' => 'vente',
            'encoder_ignored_node_types' => [
                \XML_PI_NODE, // removes XML declaration (the leading xml tag)
            ],
        ]);

        //dd($xml_vente);
        $lgoServiceJson->post_sale($xml_vente);
        $response_sales = $lgoServiceJson->getResponseInvoiceIntegration();
        $all_sales = json_decode($response_sales)->belreponse->facint->vente;
        dd($all_sales);// response for all sales integrated


        // --------------------------------   modele xml -------------------------------------------
        $num_vente = '555555558';
        $client = '<client client_id="501">
                          <nom><![CDATA[test]]></nom>
                          <prenom><![CDATA[web]]></prenom>
                          <adresse_facturation>
                             <rue1><![CDATA[rue du test]]></rue1>
                             <codepostal><![CDATA[54000]]></codepostal>
                             <ville><![CDATA[Nancy]]></ville>
                             <pays><![CDATA[FRANCE]]></pays>
                             <destinataire>![CDATA[GPB TEST]]</destinataire>
                          </adresse_facturation>
                          <sexe><![CDATA[F]]></sexe>
                       </client>';
        $date_vente = '<date_vente><![CDATA[2018-03-13T14:03:47]]></date_vente>';
        $port = '<montant_port_ht>0</montant_port_ht>
                       <tauxtva_port>20</tauxtva_port>
                       <total_ttc><![CDATA[0]]></total_ttc>
                       <exoneration_tva>0</exoneration_tva>';
        $lignes_vente = '<lignevente numero_lignevente="1">
                          <codeproduit><![CDATA[3401060160463]]></codeproduit>
                          <designation_produit><![CDATA[Tielle Lite Pans hydrocel adh10x30cm B/10 ]]></designation_produit>
                          <quantite><![CDATA[2]]></quantite>
                          <prix_brut><![CDATA[56.84]]></prix_brut>
                          <remise><![CDATA[20]]></remise>
                          <prix_net><![CDATA[45.44]]></prix_net>
                          <tauxtva><![CDATA[20]]></tauxtva>
                       </lignevente>
                       <lignevente numero_lignevente="2">
                          <codeproduit><![CDATA[3401560504477]]></codeproduit>
                          <designation_produit><![CDATA[LACTIBIANE REF GELU 10]]></designation_produit>
                          <quantite><![CDATA[2]]></quantite>
                          <prix_brut><![CDATA[11.40]]></prix_brut>
                          <remise><![CDATA[0]]></remise>
                          <prix_net><![CDATA[11.40]]></prix_net>
                          <tauxtva><![CDATA[20]]></tauxtva>
                       </lignevente>';
        $vente = '<vente num_pharma="'.$this->getParameter('login').'" numero_vente="'.$num_vente.'">'.
            $client.
            $date_vente.
            $port.
            $lignes_vente.
            '</vente>';
        dd($vente);
        $venteArray = $decoder->decode($vente, 'xml');
        //dd($venteArray);
//        $venteJson = $encoder->encode($venteArray, 'json');
//        dd(gettype($venteJson));
//        dd($json_vente);
//        $lgoServiceJson->post_sale($vente);
//        $response_sales = $lgoServiceJson->getResponseInvoiceIntegration();
//        $all_sales = json_decode($response_sales)->belreponse->facint->vente;
//        dd($all_sales);// response for all sales integrated
        return new JsonResponse('sale send with total sales: ' . $all_sales );

    }
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
                $imagePath = '/uploads/images/'.$newImagename;
                $urlImage = $request->getUriForPath($imagePath);
                $product->setImagePath($urlImage);
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
    #[Route('/api/products', name: 'app_products')]
    public function get_products(ProductsRepository $productsRepository): Response
    {
        $products = $productsRepository->findAll();

        return $this->json($products);
    }
    #[Route('/api/products/images/{idProduct}', name: "app_image_products")]
    public function get_image_products($idProduct, ProductsRepository $productsRepository, Request $request)
    {
        $product = $productsRepository->findOneBy(['id'=>$idProduct]);
        $image = $product->getImageName();
        $path_image = '/uploads/images/'.$image;
        $urlImage = $request->getUriForPath($path_image);
        return $this->json($urlImage);
    }

    /* MEDIPIM api provisoire */
    #[Route('/test/api/medipim/products', name: 'api_medipim_products')]
    public function get_medipim_products(DenormalizerInterface $denormalizer, SerializerInterface $serializer, EncoderInterface $encoder)
    {
        $apiKeyId = $this->getParameter('apiKeyId');
        $apiKeySecret = $this->getParameter('apiKeySecret');
        $baseUrl = $this->getParameter('baseUrl');

        $client = new Client($apiKeyId, $apiKeySecret, $baseUrl);

        $api_products = $client->post("/v4/products/query", [
            "filter" => [
                "hasContent" => [
                    "flag" => "media",
                    "locale" => "fr"
                ],
            ],

            "sorting" => ["id" => "ASC"],
            "page" => [
                "size" => 100,
                "no" => 0
                ]
        ]);
        //dd($api_products["results"]);
        $products = $api_products["results"];
        return $this->render('products/test.html.twig', [
            'products' => $products
        ]);
    }
}
