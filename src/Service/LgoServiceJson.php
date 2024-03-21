<?php

namespace App\Service;

class LgoServiceJson{

    private $url;
    private $page;
    private $login;
    private $pass;

    public function __construct($url, $page, $login, $pass){
        $this->url = $url;
        $this->page = $page;
        $this->login = $login;
        $this->pass = $pass;
    }

        public function getProduct(){

            $type = "STOCK"; // STOCK / VENTE / FACINT


            $ask_stock = '<?xml version="1.0" encoding="UTF-8"?>
							<beldemande date="2021-01-16" version="1.6" json="true" format="REQUEST">
							<request type="SSTOCK" num_pharma="'.$this->login.'" stock_differentiel="false"></request>
							</beldemande>';

            //on execute la requête au serveur OffiConnect
            //$fp = fopen('stock - '.$login.'.json', 'w');
            $retour_curl = $this->offiConnectRequest($this->url , $this->page , $this->login, $this->pass, $ask_stock);
            //fwrite($fp,$retour_curl);
            //fclose($fp);
            return $retour_curl;
        }

        public function post_sale($vente){
            $type = "VENTE";
            //$num_vente = 12341234; // != d'une vente existante
            $send_sale =  '<?xml version="1.0" encoding="UTF-8"?>
            <beldemande version="1.4" date="2018-03-27" format="INFACT" json="false">
              <infact>
            '.$vente.'
            </infact>
            </beldemande>';

            //pas d'�criture de fichier pour le retour de vente
            $retour_curl = $this->offiConnectRequest($this->url , $this->page , $this->login, $this->pass, $send_sale);
            echo $retour_curl;
        }
        public function getResponseInvoiceIntegration()
        {
            $type = "FACINT";

            $ask_response_invoice_integration = '<?xml version="1.0" encoding="UTF-8"?>
						<beldemande date="2013-11-28" version="1.6" json="true" format="REQUEST">
						<request type="FACINT" num_pharma="'.$this->login.'"></request>
						</beldemande>';
            $retour_curl = $this->offiConnectRequest($this->url , $this->page , $this->login, $this->pass, $ask_response_invoice_integration);

            return $retour_curl;
        }

        /*
            Fonction permettant la communication avec le serveur OffiConnect en utilisant curl
        */
        function offiConnectRequest($host, $path, $login, $pass, $data){

            //compression au format gzip du xml envoy� en param�tre
            //puis encodage du r�sultat en base64
            $data64z=base64_encode(gzencode($data));

            //initialisation de curl
            $curl = curl_init();

            //declaration / initialisations des options de curl
            curl_setopt_array($curl, array(
                //retourne le r�sulat � curl sans l'afficher
                CURLOPT_RETURNTRANSFER => 1,
                //initialisation de l'url appel�
                CURLOPT_URL => $host.$path,
                //affectation du USER AGENT
                CURLOPT_USERAGENT => 'LGPI',
                //permet d'indiquer � PHP de faire un HTTP POST
                CURLOPT_POST => 1,
                //d�finition des variables � passer lors du POST
                CURLOPT_POSTFIELDS => array(
                    'login' => $login,
                    'password' => $pass,
                    'data' => $data64z)
            ));

            //excecution de la requete
            $resp = curl_exec($curl);

            //on ferme la connexion
            curl_close($curl);

            //on retourne le r�sultat de la requ�te
            return $resp;
        }
}
