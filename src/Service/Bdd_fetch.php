<?php
namespace App\Service;

use AllowDynamicProperties;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use function MongoDB\BSON\toJSON;

#[AllowDynamicProperties] class Bdd_fetch
{
    public function __construct(private HttpClientInterface $client, ParameterBagInterface $parameterBag ,SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
        $this->parameterBag =$parameterBag;
    }

    public function urlMaker($search_string)
    {
        $url = $this->parameterBag->get('url_bdd_api');
        $id = $this->parameterBag->get('bdd_med_id');
        $key = $this->parameterBag->get('bdd_key');

        if(is_numeric($search_string)){
            return $url.'search?app_id='.$id.'&app_key='.$key.'&code='.$search_string;
        } else {
            return $url.'packages?app_id='.$id.'&app_key='.$key.'&q='.$search_string; // requete entourée par $$ permet de chercher un contenant search_string (cf doc api vidal)
        }
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function fetch_bdd_information($ean): array
    {

        $global_url = $this->urlMaker($ean);
        //dump($url);
        $response = $this->client->request(
            'GET',
            $global_url
        );

        $statusCode = $response->getStatusCode(); dump($statusCode);
        // $contentType = $response->getHeaders()['content-type'][0]; dump($contentType);
        if($statusCode == 200){
                $content = $response->getContent(); // dump($content);
                $content_bis = $this->serializer->decode($content, 'xml'); // dump($content_bis);
                $content_ter = $content_bis["entry"]; //dump($content_ter);
                return $content_ter;
        }

        return ['code'=>$statusCode];
    }
}
