<?php

namespace App\Service;

use Symfony\Component\String\Slugger\SluggerInterface;

/** 
* C'est un service qui nous permet de créer un slug avec le nom d'une association
* Grâce à un composant symfony 
* Elle nous permet aussi de gérer si la première lettre est en capitale ou non
*/
class MySlugger 
{
    private $slugger;
    private $toLower;

    public function __construct(SluggerInterface $slugger, bool $toLower)
    {
        $this->slugger = $slugger;
        $this->toLower = $toLower;
    }

    public function slugify(string $input): string
    {
        if($this->toLower) {
            $slug = $this->slugger->slug($input)->lower();
        } else {
            $slug = $this->slugger->slug($input);
        }

        return $slug;
    }
}