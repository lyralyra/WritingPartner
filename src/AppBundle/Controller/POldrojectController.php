<?php

// src/AppBundle/Controller/ProjectController.php
namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ProjectController extends Controller
{
    /**
     * Matches / exactly
     *
	 * @Route("/", name="project_list")
     */
    public function listAction()
    {
		$repository = $this->getDoctrine()->getRepository('AppBundle:Project');
		$user = $this->getUser();
		$projects = $repository->findByUser($user);
		//	 * @Route("/project/{page}", name="project_list", requirements={"page": "\d+"})
        // $characters = [
          // 'Daenerys Targaryen' => 'Emilia Clarke',
          // 'Jon Snow'           => 'Kit Harington',
          // 'Arya Stark'         => 'Maisie Williams',
          // 'Melisandre'         => 'Carice van Houten',
          // 'Khal Drogo'         => 'Jason Momoa',
          // 'Tyrion Lannister'   => 'Peter Dinklage',
          // 'Ramsay Bolton'      => 'Iwan Rheon',
          // 'Petyr Baelish'      => 'Aidan Gillen',
          // 'Brienne of Tarth'   => 'Gwendoline Christie',
          // 'Lord Varys'         => 'Conleth Hill'
        // ];

        return $this->render('project/index.html.twig', array('projects' => $projects));
    
    }

    /**
     * Matches /project/*
     *
     * @Route("/project/{id}", name="project_show", requirements={"id": "\d+"})
     */
    public function showAction($id)
    {
		//check that it's the current user's project
        $project = $this->getDoctrine()
        ->getRepository('AppBundle:Project')
        ->find($id);

		if (!$project) {
			throw $this->createNotFoundException(
            'No product found for id '.$id
			);
		}
		if($project.user != $this->getUser()){
			throw $this->createAccessDeniedException(
            'You do not have acces to this project.'
			);
		}
		// ... do something, like pass the $product object into a template
		return $this->render('project/show.html.twig', array('project' => $project));

    }
	
    /**
     * Matches /project/create
     *
     * @Route("/project/create", name="project_create")
     */
	 public function createAction()
	{
		$user = $this->getUser();
		$project = new Project();
		$project->setTitle('Keyboard');
		$project->setUser($user);
		$project->setDescription('Ergonomic and stylish!');

		$em = $this->getDoctrine()->getManager();

		// tells Doctrine you want to (eventually) save the Project (no queries yet)
		$em->persist($project);

		// actually executes the queries (i.e. the INSERT query)
		$em->flush();

		return new Response('Saved new project with id '.$project->getId());
	}
	
	/**
     * Matches /project/edit
     *
     * @Route("/project/edit/{id}", name="project_edit", requirements={"id": "\d+"})
     */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository('AppBundle:Project')->find($id);

		if (!$project) {
			throw $this->createNotFoundException(
				'No product found for id '.$id
			);
		}

		return $this->render('project/edit.html.twig', array('project' => $project));
	}
	
	/**
     * Matches /project/update
     *
     * @Route("/project/update/{id}", name="project_update", requirements={"id": "\d+"})
     */
	public function updateAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository('AppBundle:Project')->find($id);

		if (!$project) {
			throw $this->createNotFoundException(
				'No product found for id '.$id
			);
		}

		$project->setTitle('New project title!');
		$project->setDescription('New project description!');
		$em->flush();

		return $this->redirectToRoute('project_show', array('id' => $id));
	}

	/**
     * Matches /project/delete
     *
     * @Route("/project/delete/{id}", name="project_delete", requirements={"id": "\d+"})
     */
	public function deleteAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$project = $em->getRepository('AppBundle:Project')->find($id);

		if (!$project) {
			throw $this->createNotFoundException(
				'No project found for id '.$id
			);
		}

		$em->remove($project);
		$em->flush();

		//flash message to confirm deletion
		return $this->redirectToRoute('project_list');
	}	
}