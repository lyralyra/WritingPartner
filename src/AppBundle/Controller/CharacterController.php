<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Character controller.
 *
 * @Route("character")
 */
class CharacterController extends Controller
{
    // /**
     // * Lists all character entities.
     // *
     // * @Route("/", name="character_index")
     // * @Method("GET")
     // */
    // public function indexAction(Project $project)
    // {
        // //$em = $this->getDoctrine()->getManager();

        // $characters = $project->getCharacters(); //$em->getRepository('AppBundle:Character')->findAll();

        // return $this->render('character/index.html.twig', array(
            // 'characters' => $characters,
        // ));
    // }

    /**
     * Creates a new character entity.
     *
     * @Route("/new/{projectId}", name="character_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $projectId)
    {
        $character = new Character();
		$em = $this->getDoctrine()->getManager();
		//error if no $projectId
		$project = $em->getRepository('AppBundle:Project')->find($projectId);
		//error if no $project
		$character->addProject($project);
        $form = $this->createForm('AppBundle\Form\CharacterType', $character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($character);
            $em->flush();
			
			$project->addCharacter($character);
			$em->persist($project);
            $em->flush();
			
            return $this->redirectToRoute('character_show', array('id' => $character->getId()));
        }

		
        return $this->render('character/new.html.twig', array(
            'character' => $character,
            'form' => $form->createView(),
			'projectId' => $projectId
        ));
    }

    /**
     * Finds and displays a character entity.
     *
     * @Route("/{id}", name="character_show")
     * @Method("GET")
     */
    public function showAction(Character $character)
    {
        $deleteForm = $this->createDeleteForm($character);

        return $this->render('character/show.html.twig', array(
            'character' => $character,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing character entity.
     *
     * @Route("/{id}/edit", name="character_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Character $character)
    {
        $deleteForm = $this->createDeleteForm($character);
        $editForm = $this->createForm('AppBundle\Form\CharacterType', $character);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('character_edit', array('id' => $character->getId()));
        }

        return $this->render('character/edit.html.twig', array(
            'character' => $character,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a character entity.
     *
     * @Route("/{id}", name="character_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Character $character)
    {
        $form = $this->createDeleteForm($character);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($character);
            $em->flush();
        }

        return $this->redirectToRoute('character_index');
    }

    /**
     * Creates a form to delete a character entity.
     *
     * @param Character $character The character entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Character $character)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('character_delete', array('id' => $character->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
