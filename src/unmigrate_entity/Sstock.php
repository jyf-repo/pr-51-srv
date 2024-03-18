<?php

namespace App\unmigrate_entity;

class Sstock
{
    private array $produit;

    /**
     * @return mixed
     */
    public function getProduit(): array
    {
        return $this->produit;
    }

    /**
     * @param mixed $produit
     */
    public function setProduit(array $produit): void
    {
        $this->produit = $produit;
    }

}
