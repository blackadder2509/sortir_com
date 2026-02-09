<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
$builder
->add('q', TextType::class, [
'label' => false,
'required' => false,
'attr' => [
'placeholder' => 'Rechercher'
]
])
->add('campus', EntityType::class, [
'label' => false,
'required' => false,
'class' => Campus::class,
'choice_label' => 'nom', // Assure-toi que ta propriété s'appelle bien 'nom' dans l'entité Campus
'placeholder' => 'Campus'
])
;
}

public function configureOptions(OptionsResolver $resolver)
{
$resolver->setDefaults([
'data_class' => SearchData::class,
'method' => 'GET', // IMPORTANT : On veut voir les paramètres dans l'URL
'csrf_protection' => false // Pas nécessaire pour une recherche simple
]);
}
}
