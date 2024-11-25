<?php

class sites
{
    private $id;
    private $nom_site;
    private $description_site;
    private $category; // ID de la catégorie (clé étrangère)
    private $images;

    // Le constructeur n'a pas besoin de demander l'ID de catégorie si connu au préalable
    public function __construct($id = null, $nom_site, $description_site, $category, $images)
    {
        $this->id = $id; // Peut être null pour un nouveau site
        $this->nom_site = $nom_site;
        $this->description_site = $description_site;
        $this->category = $category; // ID de la catégorie connue
        $this->images = $images;
    }

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of nom_site
     */ 
    public function getNomSite()
    {
        return $this->nom_site;
    }

    /**
     * Set the value of nom_site
     *
     * @return  self
     */ 
    public function setNomSite($nom_site)
    {
        $this->nom_site = $nom_site;

        return $this;
    }

    /**
     * Get the value of description_site
     */ 
    public function getDescriptionSite()
    {
        return $this->description_site;
    }

    /**
     * Set the value of description_site
     *
     * @return  self
     */ 
    public function setDescriptionSite($description_site)
    {
        $this->description_site = $description_site;

        return $this;
    }

    /**
     * Get the value of category
     */ 
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set the value of category
     *
     * @return  self
     */ 
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get the value of images
     */ 
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set the value of images
     *
     * @return  self
     */ 
    public function setImages($images)
    {
        $this->images = $images;

        return $this;
    }
}

?>
