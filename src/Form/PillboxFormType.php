<?php

namespace App\Form;

use App\Entity\Pillbox;
use App\Entity\User;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PillboxFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('start', DateType::class, [
                'required'=> true
            ])
            ->add('end', DateTimeType::class, [
                'required' => true
            ])
            ->add('Price', TextType::class, [
                'required' => true
            ])
            ->add('payed')
            ->add('comments')
            ->add('userId', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Valider'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pillbox::class,
        ]);
    }
}
