<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 05/02/18
 * Time: 16:25
 */
namespace AppBundle\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ShowType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name', TextType::class)
            ->add('author', TextType::class)
            ->add('published_date', DateType::class)
            ->add('save', SubmitType::class, array('label' => 'Create show'));
    }

}