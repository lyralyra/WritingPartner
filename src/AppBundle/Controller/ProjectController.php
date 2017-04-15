<?php

// src/AppBundle/Controller/ProjectController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProjectController extends Controller
{
    /**
     * Matches /project exactly
     *
	 * @Route("/project/{page}", name="project_list", requirements={"page": "\d+"})
     */
    public function listAction($page = 1)
    {
        $characters = [
          'Daenerys Targaryen' => 'Emilia Clarke',
          'Jon Snow'           => 'Kit Harington',
          'Arya Stark'         => 'Maisie Williams',
          'Melisandre'         => 'Carice van Houten',
          'Khal Drogo'         => 'Jason Momoa',
          'Tyrion Lannister'   => 'Peter Dinklage',
          'Ramsay Bolton'      => 'Iwan Rheon',
          'Petyr Baelish'      => 'Aidan Gillen',
          'Brienne of Tarth'   => 'Gwendoline Christie',
          'Lord Varys'         => 'Conleth Hill'
        ];

        return $this->render('default/index.html.twig', array('character' => $characters));
    
    }

    /**
     * Matches /project/*
     *
     * @Route("/project/{slug}", name="project_show")
     */
    public function showAction($slug)
    {
        // $slug will equal the dynamic part of the URL
        // e.g. at /project/yay-routing, then $slug='yay-routing'

        // ...
    }
	
    /**
     * Matches /project/create
     *
     * @Route("/project/create", name="project_create")
     */
	 public function createAction()
	{
		
	}
}