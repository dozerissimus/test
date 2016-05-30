<?php
namespace Gallery\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('gender', 'choice', ['choices' => ['male' => 'Male', 'female' => 'Female']]);
        $builder->add('bidthdate', 'birthday');
    }
    
    public function getName()
    {
        return 'gallery_user_registration';
    }
}

