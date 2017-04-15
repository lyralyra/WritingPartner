<?php

// src/AppBundle/Controller/ProjectController.php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class MainController extends Controller
{

    public function showAction($slug)
    {
        // ...

        // /blog/my-blog-post
        $url = $this->generateUrl(
            'project_show',
            array('slug' => 'my-project-page')
        );
    }
	
}