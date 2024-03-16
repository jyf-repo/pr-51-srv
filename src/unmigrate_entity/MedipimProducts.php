<?php

namespace App\unmigrate_entity;

class MedipimProducts
{
    private string $id;
    private string $acl13;
    private array $nameCti;
    private array $name;
    private string $status;
    private bool $prescription;
    private bool $requiresLegalText;
    private int $pharmacistPrice;
    private int $publicPrice;
    private int $tfrPrice;
    private int $reimbursementRate;
    private array $publicCategories;
    private array $seoName;
    private array $shortDescription;
    private string $descriptions;
    private array $organizations;
    private array $photos;
    private array $meta;

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string|null $id
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getAcl13(): ?string
    {
        return $this->acl13;
    }

    /**
     * @param string|null $acl13
     */
    public function setAcl13(?string $acl13): void
    {
        $this->acl13 = $acl13;
    }

    /**
     * @return array|null
     */
    public function getNameCti(): ?array
    {
        return $this->nameCti;
    }

    /**
     * @param array|null $nameCti
     */
    public function setNameCti(?array $nameCti): void
    {
        $this->nameCti = $nameCti;
    }

    /**
     * @return array|null
     */
    public function getName(): ?array
    {
        return $this->name;
    }

    /**
     * @param array|null $name
     */
    public function setName(?array $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     */
    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return bool|null
     */
    public function getPrescription(): ?bool
    {
        return $this->prescription;
    }

    /**
     * @param bool|null $prescription
     */
    public function setPrescription(?bool $prescription): void
    {
        $this->prescription = $prescription;
    }

    /**
     * @return bool|null
     */
    public function getRequiresLegalText(): ?bool
    {
        return $this->requiresLegalText;
    }

    /**
     * @param bool|null $requiresLegalText
     */
    public function setRequiresLegalText(?bool $requiresLegalText): void
    {
        $this->requiresLegalText = $requiresLegalText;
    }

    /**
     * @return int|null
     */
    public function getPharmacistPrice(): ?int
    {
        return $this->pharmacistPrice;
    }

    /**
     * @param int|null $pharmacistPrice
     */
    public function setPharmacistPrice(?int $pharmacistPrice): void
    {
        $this->pharmacistPrice = $pharmacistPrice;
    }

    /**
     * @return int|null
     */
    public function getPublicPrice(): ?int
    {
        return $this->publicPrice;
    }

    /**
     * @param int|null $publicPrice
     */
    public function setPublicPrice(?int $publicPrice): void
    {
        $this->publicPrice = $publicPrice;
    }

    /**
     * @return int|null
     */
    public function getTfrPrice(): ?int
    {
        return $this->tfrPrice;
    }

    /**
     * @param int|null $tfrPrice
     */
    public function setTfrPrice(?int $tfrPrice): void
    {
        $this->tfrPrice = $tfrPrice;
    }

    /**
     * @return int|null
     */
    public function getReimbursementRate(): ?int
    {
        return $this->reimbursementRate;
    }

    /**
     * @param int|null $reimbursementRate
     */
    public function setReimbursementRate(?int $reimbursementRate): void
    {
        $this->reimbursementRate = $reimbursementRate;
    }

    /**
     * @return array|null
     */
    public function getPublicCategories(): ?array
    {
        return $this->publicCategories;
    }

    /**
     * @param array|null $publicCategories
     */
    public function setPublicCategories(?array $publicCategories): void
    {
        $this->publicCategories = $publicCategories;
    }

    /**
     * @return array|null
     */
    public function getSeoName(): ?array
    {
        return $this->seoName;
    }

    /**
     * @param array|null $seoName
     */
    public function setSeoName(?array $seoName): void
    {
        $this->seoName = $seoName;
    }

    /**
     * @return array|null
     */
    public function getShortDescription(): ?array
    {
        return $this->shortDescription;
    }

    /**
     * @param array|null $shortDescription
     */
    public function setShortDescription(?array $shortDescription): void
    {
        $this->shortDescription = $shortDescription;
    }

    /**
     * @return string|null
     */
    public function getDescriptions(): ?string
    {
        return $this->descriptions;
    }

    /**
     * @param string|null $descriptions
     */
    public function setDescriptions(?string $descriptions): void
    {
        $this->descriptions = $descriptions;
    }

    /**
     * @return array|null
     */
    public function getOrganizations(): ?array
    {
        return $this->organizations;
    }

    /**
     * @param array|null $organizations
     */
    public function setOrganizations(?array $organizations): void
    {
        $this->organizations = $organizations;
    }

    /**
     * @return array|null
     */
    public function getPhotos(): ?array
    {
        return $this->photos;
    }

    /**
     * @param array|null $photos
     */
    public function setPhotos(?array $photos): void
    {
        $this->photos = $photos;
    }

    /**
     * @return array|null
     */
    public function getMeta(): ?array
    {
        return $this->meta;
    }

    /**
     * @param array|null $meta
     */
    public function setMeta(?array $meta): void
    {
        $this->meta = $meta;
    }


}
