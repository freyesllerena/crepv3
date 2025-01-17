<?php

namespace AppBundle\Form\Crep\CrepMcc02;

use AppBundle\Entity\Crep\CrepMcc02\CrepMcc02;
use AppBundle\Form\AutreObjectifType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02AutresPotentielEvolutionType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02CompetenceActionType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02CompetenceDemontreeType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02CompetenceRelationType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02CompetenceRequiseType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02CompetenceSituationType;
use AppBundle\Form\Crep\CrepMcc02\Competences\CrepMcc02PotentielEvolutionType;
use AppBundle\Form\Crep\CrepMcc02\Formations\CrepMcc02FormationSuivieType;
use AppBundle\Form\Crep\CrepMcc02\Formations\CrepMcc02FormationT1Type;
use AppBundle\Form\Crep\CrepMcc02\Formations\CrepMcc02FormationT2Type;
use AppBundle\Form\Crep\CrepMcc02\Formations\CrepMcc02FormationT3Type;
use AppBundle\Form\Crep\CrepType;
use AppBundle\Form\ObjectifEvalueType;
use AppBundle\Form\ObjectifFuturType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CrepMcc02Type extends CrepType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* @var $crep CrepMcc02 */
        $crep = $builder->getData();
        $options['echelleObjectifEvalue'] = $crep::$echelleObjectifEvalue;
        $options['ministere'] = $crep->getAgent()->getCampagnePnc()->getMinistere();
        $options['anneeEvaluee']  = $crep->getAgent()->getCampagnePnc()->getAnneeEvaluee();

        parent::buildForm($builder, $options);

        $echelleObjectifEvalue = $options['echelleObjectifEvalue'];
        $ministere = $crep->getAgent()->getCampagnePnc()->getMinistere();
        $tableauNotesAgent = [];
        
        for($i=1 ; $i<=20 ; $i++){
        	$tableauNotesAgent[$i.'/20'] = $i;
        }

        $builder
            ->add('nomUsage', null, ['required' => true])
            ->add('prenom')
            ->add('dateNaissance',
                DateType::class,
                array(
                    'label' => false,
                    'widget' => 'single_text',
                    'input'  => 'datetime',
                    'format' => 'dd/MM/yyyy',
                    'required' => false,
                    )
                )
            ->add('grade', null, ['required' => false])
            ->add('corps', null, ['required' => false])
            ->add('echelon', null, ['required' => false])
            ->add('posteOccupe', null, ['required' => false])
            ->add('affectation', null, ['required' => false])
            ->add('emploiFonctionnel', ChoiceType::class,
                [
                    'choices' => [
                        'Oui' => true,
                        'Non' => false,
                    ],
                    'expanded' => true,
                    'placeholder' => null,
                    'required' =>false,
                    'multiple' => false,
                ])
            ->add('libelleEmploiFonctionnel', null, ['required' => false])
            ->add('groupeEmploiFonctionnel', null, ['required' => false])
            ->add('groupeRifseep', null, [
                    'attr' => ['maxlength' => '255'],
                    'required' => false
                ]
            )
            ->add('fonctionsExercees', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('groupeFonctions', TextareaType::class, [
                'attr' => ['maxlength' => '255'],
                'required' => false
            ])
            ->add('fichePosteAdaptee', ChoiceType::class,
                [
                    'choices' => [
                        'Oui' => true,
                        'Non' => false,
                    ],
                    'expanded' => true,
                    'placeholder' => null,
                    'required' =>false,
                    'multiple' => false,
                ])
            ->add('pointsActualisesFichePoste', null, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])

            ->add('observationsObjectifsPasses', null, [ "attr"=> ["maxlength" => "4096"], 'required' => false,])
            ->add('docAnnexeBilan', ChoiceType::class,
                [
                    'choices' => [
                        'Oui' => true,
                        'Non' => false,
                    ],
                    'expanded' => true,
                    'placeholder' => null,
                    'required' =>false,
                    'multiple' => false,
                ])
            ->add('contexteAnneeEcoulee', null, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add(
                'objectifsEvalues',
                CollectionType::class,
                [
                    'entry_type' => ObjectifEvalueType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => ['echelleObjectifEvalue' => $echelleObjectifEvalue],
                ]
            )
            ->add('autresObjectifs',
                CollectionType::class,
                [
                    'entry_type' => AutreObjectifType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )

            ->add('natureDossiersTravaux', null, ['required' => false])
            ->add('resultatsObtenusParAgent', null, ['required' => false])
            ->add('contexteResultats', null, ['required' => false])

            ->add('docAnnexeObjectifsAvenir', ChoiceType::class,
                [
                    'choices' => [
                        'Oui' => true,
                        'Non' => false,
                    ],
                    'expanded' => true,
                    'placeholder' => null,
                    'required' =>false,
                    'multiple' => false,
                ])
            ->add('contexteObjectifsAvenir', TextareaType::class, [
                "attr"=> ["maxlength" => "4096"],
                'required' => false,])
            ->add('objectifsFuturs',
                CollectionType::class,
                [
                    'entry_type' => ObjectifFuturType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            // IV
            ->add('competencesActions',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02CompetenceActionType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'by_reference' => false,
                ]
            )
            ->add('observationsCompetencesActions', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('competencesRelations',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02CompetenceRelationType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'by_reference' => false,
                ]
            )
            ->add('observationsCompetencesRelations', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('competencesSituations',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02CompetenceSituationType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'by_reference' => false,
                ]
            )
            ->add('observationsCompetencesSituations', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('competencesRequises',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02CompetenceRequiseType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add('observationsCompetencesRequises', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('competencesDemontrees',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02CompetenceDemontreeType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add('observationsCompetencesDemontrees', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            // V
            ->add('souhaitEvolutionCarriere', null, ['required' => false])
            ->add('mobilitePoste', CrepMcc02MobilitePosteType::class, ['required' => false])

            ->add('mobiliteGeographique', CrepMcc02MobiliteGeographiqueType::class, ['required' => false])

            ->add('evolutionProfessionnelleEnvisagee', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('souhaitEntretienCarriere', ChoiceType::class, [
                'choices' => [
                    'Oui'       => 1,
                    'Non'       => 0,
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => false,
                'placeholder' => null,
            ])
            ->add('observationsShdProjetProfessionnel', TextareaType::class, [
                'attr' => ['maxlength' => '4096'],
                'required' => false
            ])
            ->add('dateEntreePoste',
            		DateType::class,
            		array(
            				'label' => false,
            				'widget' => 'single_text',
            				'input'  => 'datetime',
            				'format' => 'dd/MM/yyyy',
            				'required' => false,
            		)
            )
            ->add('nomUsageShd', null, ['required' => false])
            ->add('prenomShd', null, ['required' => false])
            ->add('corpsShd', null, ['required' => false])
            ->add('gradeShd', null, ['required' => false])
            ->add('posteOccupeShd', null, ['required' => false])
            ->add('dateEntreePosteOccupeShd',
                DateType::class,
                array(
                    'label' => false,
                    'widget' => 'single_text',
                    'input'  => 'datetime',
                    'format' => 'dd/MM/yyyy',
                    'required' => false,
                )
            )
            ->add('affectationShd', null, ['required' => false])
            ->add('posteOccupeShd', null, ['required' => false])
            ->add(
                'formationsSuivies',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02FormationSuivieType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => [
                        'ministere' => $ministere,
                        'anneeEvaluation' => $options['anneeEvaluee'],
                    ],
                ]
            )
            ->add(
                'formationsT1',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02FormationT1Type::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => ['annee_evaluee' => $options['anneeEvaluee']],
                ]
            )
            ->add(
                'formationsT2',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02FormationT2Type::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => ['annee_evaluee' => $options['anneeEvaluee']],
                ]
            )
            ->add(
                'formationsT3',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02FormationT3Type::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_options' => ['annee_evaluee' => $options['anneeEvaluee']],
                ]
            )

            // VII
            ->add('appreciationGenerale', null, [
                'attr' => ['maxlength' => '4096'],
                'required' => false, ])
            ->add(
                'potentielsEvolutions',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02PotentielEvolutionType::class,
                    'allow_add' => false,
                    'allow_delete' => false,
                    'by_reference' => false,
                ]
            )
            ->add(
                'autresPotentielsEvolutions',
                CollectionType::class,
                [
                    'entry_type' => CrepMcc02AutresPotentielEvolutionType::class,
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                ]
            )
            ->add('observationsPotentielEvolutionAgent', null, [
                'attr' => ['maxlength' => '4096'],
                'required' => false, ])
            ->add('attributionCia', ChoiceType::class, [
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('explicationAttributionCia', null, ['attr' => ['maxlength' => '4096'], 'required' => false])

            ->add('propositionAvancement', ChoiceType::class, [
                'choices' => [
                    'Oui' => 0,
                    'Non' => 1,
                    'Sans objet' => 2,
                ],
                'expanded' => true,
                'multiple' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Crep\CrepMcc02\CrepMcc02',
            'echelleObjectifEvalue' => null,
            'echelleNiveauSame' => null,
            'ministere'=> null,
        	'selectTypologieFormation' => null,
        ));
    }
}
