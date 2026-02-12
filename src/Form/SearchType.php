<?php

namespace App\Form;

use App\Entity\Campus;
use App\Model\SearchData;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'label' => 'Le nom contient :',
                'required' => false,
                'attr' => ['placeholder' => 'Rechercher']
            ])
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'nom',
                'placeholder' => 'Tous les campus',
                'required' => false
            ])
            ->add('datemin', DateTimeType::class, [
                'label' => 'Entre le',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('datemax', DateTimeType::class, [
                'label' => 'et le',
                'widget' => 'single_text',
                'required' => false
            ])
            ->add('isOrganisateur', CheckboxType::class, ['label' => "Sorties dont je suis l'organisateur", 'required' => false])
            ->add('isInscrit', CheckboxType::class, ['label' => "Sorties auxquelles je suis inscrit", 'required' => false])
            ->add('isNotInscrit', CheckboxType::class, ['label' => "Sorties auxquelles je ne suis pas inscrit", 'required' => false])
            ->add('isPassee', CheckboxType::class, ['label' => "Sorties passées", 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
}
