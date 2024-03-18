<?php

namespace App\unmigrate_entity;

class Rootrequest
{
    private $belreponse;

    /**
     * @return mixed
     */
    public function getBelreponse()
    {
        return $this->belreponse;
    }

    /**
     * @param mixed $belreponse
     */
    public function setBelreponse(Belreponse $belreponse): void
    {
        $this->belreponse = $belreponse;
    }
}
