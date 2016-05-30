<?php
namespace Gallery\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder->add('name');
        $builder->add('gender', 'choice', ['choices' => ['male' => 'Male', 'female' => 'Female']]);
        $builder->add('bidthdate', 'birthday');
        //$builder->add('file', 'file', ['required' => false]);
        $builder->add('home');
        $builder->add('avatar', 'hidden');
        $builder->add('for_update', 'hidden');
        $builder->remove('username');
        $builder->add('temp_name', 'hidden');
        $builder->add('temp_md5', 'hidden');
    }
    
    public function getName()
    {
        return 'gallery_user_profile';
    }
}

