<?php

namespace App\unmigrate_entity;

class Belreponse
{
    private $sstock;

    /**
     * @return mixed
     */
    public function getSstock()
    {
        return $this->sstock;
    }

    /**
     * @param mixed $sstock
     */
    public function setSstock(Sstock $sstock): void
    {
        $this->sstock = $sstock;
    }

}
