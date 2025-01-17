<?php

namespace AppBundle\Repository\CrepRepository\CrepScl02Repository;

use AppBundle\Entity\CampagneBrhp;
use AppBundle\EnumTypes\EnumStatutCrep;

/**
 * CrepScl02Repository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CrepScl02FormationEnvisageeRepository extends \Doctrine\ORM\EntityRepository
{
    public function exportFormations(CampagneBrhp $campagneBrhp, $modeleCrep)
    {
        $qb = $this->createQueryBuilder('formation');
        $qb
        ->select('agent.matricule as a_matricule, agent.email as a_email, agent.civilite as a_civilite, agent.nom as a_nom,  agent.prenom as a_prenom')
        ->addSelect('agent.categorieAgent as a_categorieAgent, agent.corps as a_corps, agent.grade as a_grade, agent.affectation as a_affectation')
        ->addSelect('crep.dateNotification as c_dateNotification, crep.dateRefusNotification as c_dateRefusNotification')

        ->addSelect('formation.libelle as f_libelle')
        ->addSelect('formation.origine as f_origine')

        ->innerJoin('formation.crep', 'crep')
        ->innerJoin('crep.agent', 'agent')
        ->where('agent.campagneBrhp = :CAMPAGNE_BRHP')
        ->andWhere('crep.statut IN(:STATUTS_CREPS_FINALISE)')
        ->andWhere('crep.crepPapier IS NULL')
        ->andWhere('crep INSTANCE OF '.$modeleCrep)
        ->orderBy('agent.nom')
        ->addOrderBy('agent.prenom')
        ->setParameter('CAMPAGNE_BRHP', $campagneBrhp)
        ->setParameter('STATUTS_CREPS_FINALISE', [EnumStatutCrep::NOTIFIE_AGENT, EnumStatutCrep::REFUS_NOTIFICATION_AGENT]);

        $reslut = $qb->getQuery()->getScalarResult();

        return $reslut;
    }
}
