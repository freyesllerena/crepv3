<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\PerimetreBrhp;
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Rlc;
use AppBundle\Security\PerimetreBrhpVoter;
use AppBundle\Entity\Brhp;
use AppBundle\Service\PerimetreBrhpManager;
use AppBundle\Entity\UniteOrganisationnelle;
use AppBundle\Entity\BrhpConsult;

/**
 * PerimetreBrhp controller.
 */
class PerimetreBrhpController extends Controller
{
    /**
     * Lists all PerimetreBrhp entities.
     *
     * @Security("has_role('ROLE_RLC') or has_role('ROLE_BRHP') or has_role('ROLE_BRHP_CONSULT')")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        /* @var $user Utilisateur */
        $utilisateur = $this->getUser();

        $selectedRole = $this->get('session')->get('selectedRole');

        if ('ROLE_ADMIN' === $selectedRole) {
            $perimetreBrhps = $em->getRepository('AppBundle:PerimetreBrhp')->findAll();
        } elseif ('ROLE_BRHP' === $selectedRole) {
            /* @var $brhp Brhp */
            $brhp = $em->getRepository('AppBundle:Brhp')->findOneByUtilisateur($utilisateur);
            $perimetreBrhps = $brhp->getPerimetresBrhp();
        } elseif ('ROLE_BRHP_CONSULT' === $selectedRole) {
            /* @var $brhpConsult BrhpConsult */
            $brhpConsult = $em->getRepository('AppBundle:BrhpConsult')->findOneByUtilisateur($utilisateur);
            $perimetreBrhps = $brhpConsult->getPerimetresBrhp();
        } else {
            $rlc = $em->getRepository('AppBundle:Rlc')->findOneByUtilisateur($utilisateur);
            $perimetreBrhps = $em->getRepository('AppBundle:PerimetreBrhp')->getPerimetresBrhpByRlc($rlc);
        }

        $deleteForms = $this->createDeleteForms($perimetreBrhps);

        return $this->render('perimetreBrhp/index.html.twig', array(
                'perimetreBrhps' => $perimetreBrhps,
                'deleteForms' => $deleteForms,
        ));
    }

    /**
     * Creates a new PerimetreBrhp entity.
     *
     * @Security("has_role('ROLE_RLC')")
     */
    public function newAction(Request $request)
    {
        $perimetreBrhp = new PerimetreBrhp();

        $em = $this->getDoctrine()->getManager();

        $utilisateur = $this->getUser();
        $ministere = $utilisateur->getMinistere();

        if ($utilisateur->hasRole('ROLE_ADMIN')) {
            $perimetresRlc = $em->getRepository('AppBundle:PerimetreRlc')->findAll();
        } else {
            $rlc = $em->getRepository('AppBundle:Rlc')->findOneByUtilisateur($utilisateur);
            $perimetresRlc = $rlc->getPerimetresRlc();
        }

        $unitesOrganisationnellesOrphelines = $em->getRepository('AppBundle:UniteOrganisationnelle')->getUnitesOrganisationnellesSansPerimetreBrhp($ministere);

        $form = $this->createForm('AppBundle\Form\PerimetreBrhpType', $perimetreBrhp, array(
                'perimetresRlc' => $perimetresRlc,
                'unitesOrganisationnelles' => $unitesOrganisationnellesOrphelines,
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($perimetreBrhp);
            $em->flush();

            $this->get('session')->getFlashBag()->set('notice', 'Périmètre BRHP créé !');

            return $this->redirectToRoute('perimetre_brhp_index', array(
                    'id' => $perimetreBrhp->getId(),
            ));
        }

        return $this->render('perimetreBrhp/new.html.twig', array(
                'perimetreBrhp' => $perimetreBrhp,
                'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PerimetreBrhp entity.
     *
     * @Security("has_role('ROLE_RLC')")
     */
    public function editAction(Request $request, PerimetreBrhp $perimetreBrhp, PerimetreBrhpManager $perimetreBrhpManager)
    {
        // Voter
        $this->denyAccessUnlessGranted(PerimetreBrhpVoter::MODIFIER, $perimetreBrhp);

        $anciennesUos = $perimetreBrhp->getUnitesOrganisationnelles()->toArray();

        $em = $this->getDoctrine()->getManager();
        $utilisateur = $this->getUser();

        if ($utilisateur->hasRole('ROLE_ADMIN')) {
            $perimetresRlc = $em->getRepository('AppBundle:PerimetreRlc')->findAll();
        } else {
            $rlc = $em->getRepository('AppBundle:Rlc')->findOneByUtilisateur($utilisateur);
            $perimetresRlc = $rlc->getPerimetresRlc();
        }

        $ministere = $perimetreBrhp->getPerimetreRlc()->getMinistere();

        $unitesOrganisationnellesSansPerimetreBrhp = $em->getRepository('AppBundle:UniteOrganisationnelle')->getUnitesOrganisationnellesSansPerimetreBrhp($ministere);
        $unitesOrganisationnelles = array_merge($perimetreBrhp->getUnitesOrganisationnelles()->toArray(), $unitesOrganisationnellesSansPerimetreBrhp);

        $editForm = $this->createForm('AppBundle\Form\PerimetreBrhpType', $perimetreBrhp, array(
                    'perimetresRlc' => $perimetresRlc,
                    'unitesOrganisationnelles' => $unitesOrganisationnelles,
        ));

        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $perimetreBrhpManager->save($perimetreBrhp, $anciennesUos);

            $this->get('session')->getFlashBag()->set('notice', 'Périmètre BRHP modifié !');

            return $this->redirectToRoute('perimetre_brhp_index', array(
                        'id' => $perimetreBrhp->getId(),
                ));
        }

        return $this->render('perimetreBrhp/edit.html.twig', array(
                    'perimetreBrhp' => $perimetreBrhp,
                    'form' => $editForm->createView(),
            ));
    }

    /**
     * Show a PerimetreBrhp entity.
     *
     * @Security("has_role('ROLE_RLC') or has_role('ROLE_BRHP') or has_role('ROLE_BRHP_CONSULT')")
     */
    public function showAction(Request $request, PerimetreBrhp $perimetreBrhp)
    {
        //Voter
        $this->denyAccessUnlessGranted(PerimetreBrhpVoter::VOIR, $perimetreBrhp);

        return $this->render('perimetreBrhp/show.html.twig', array(
            'perimetreBrhp' => $perimetreBrhp,
        ));
    }

    /**
     * Deletes a PerimetreBrhp entity.
     *
     * @Security("has_role('ROLE_RLC')")
     */
    public function deleteAction(Request $request, PerimetreBrhp $perimetreBrhp)
    {
        //Voter
        $this->denyAccessUnlessGranted(PerimetreBrhpVoter::SUPPRIMER, $perimetreBrhp);

        $form = $this->createDeleteForm($perimetreBrhp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
            	
            	// Suppression de la relation UO <-> Périmètre BRHP
            	$uos = $perimetreBrhp->getUnitesOrganisationnelles();
            	
            	/* @var $uo UniteOrganisationnelle */
            	foreach ($uos as $uo){
            		$uo->setPerimetreBrhp(null);
            	}
            	
                $em = $this->getDoctrine()->getManager();
                $em->remove($perimetreBrhp);
                $em->flush();
                $this->get('session')->getFlashBag()->set('notice', 'Périmètre BRHP supprimé !');
            } catch (\Exception $e) {
                $this
                    ->get('session')
                    ->getFlashBag()
                    ->set('danger', 'Suppression impossible. Le périmètre est attribué à un ou plusieurs BRHP !')
                ;
            }
        }

        return $this->redirectToRoute('perimetre_brhp_index');
    }

    /**
     * Creates a form to delete a PerimetreBrhp entity.
     *
     * @param PerimetreBrhp $perimetreBrhp
     *                                     The PerimetreBrhp entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PerimetreBrhp $perimetreBrhp)
    {
        return $this->createFormBuilder()->setAction($this->generateUrl('perimetre_brhp_delete', array(
                'id' => $perimetreBrhp->getId(),
        )))->setMethod('DELETE')->getForm();
    }

    private function createDeleteForms($perimetreBrhps)
    {
        $deleteForms = array();

        foreach ($perimetreBrhps as $perimetreBrhp) {
            $deleteForms[] = $this->createDeleteForm($perimetreBrhp)->createView();
        }

        return $deleteForms;
    }
}
