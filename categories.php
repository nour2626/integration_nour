<?php

class categories{
    private $id;
    private $nom_categorie;
    private $nb_sites;
    private $description;


    public function __construct($id,$nom_categorie,$nb_sites,$description) {
        $this->id=$id;
        $this->nom_categorie = $nom_categorie;
        $this->nb_sites = $nb_sites;
        $this->description = $description;

    }
    
   /**
     * Get the value of id
     */ 
    public function getid()
    {
        return $this->id;
    }

    /**
     * Get the value of name
     */ 
    public function getnom_categorie()
    {
        return $this->nom_categorie;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setnom_categorie($nom_categorie)
    {
        $this->nom_categorie = $nom_categorie;

        return $this;
    }

    /**
     * Get the value of nb_sites
     */ 
    public function getnb_sites()
    {
        return $this->nb_sites;
    }

    /**
     * Set the value of nb_sites
     *
     * @return  self
     */ 
    public function setnb_sites($nb_sites)
    {
        $this->nb_sites = $nb_sites;

        return $this;
    }
/**
     * Get the value of description
     */ 
    public function getdescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setdescription($description)
    {
        $this->description = $description;

        return $this;
    }
 
}

?>