<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email_contact', EmailType::class, [
                'label' => 'Votre Email',
                'attr' => [ 'placeholder' => 'toto@test.com'],
                'required' => true
            ])
            ->add('email_subject', TextType::class, [
                'label' => 'Sujet',
                'attr' => [ 'placeholder' => 'Objet de votre envoi'],
                'required' => true
            ])
            ->add('email_content', TextareaType::class, [
                'label' => 'Votre message: ',
                'attr' => [ 'placeholder' => 'Contenu du message'],
                'required' => true
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary btn-sm'],
                'label' => 'Envoyer'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
