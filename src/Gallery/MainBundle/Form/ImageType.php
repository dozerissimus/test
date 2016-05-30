<?php

namespace Gallery\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('date', 'hidden')
            ->add('autor', 'hidden')
            ->add('description')
            ->add('choice_category', 'hidden')
            ->add('temp_name', 'hidden')
            ->add('temp_md5', 'hidden')
            //->add('file', 'file', ['required' => false])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gallery\MainBundle\Entity\Image'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gallery_mainbundle_image';
    }
}
