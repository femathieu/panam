<?php
namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CategoryType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $category = $options["data"];
        $builder
            ->add('label', TextType::class)
            ->add('isActive', CheckBoxType::class, array(
                'label' => 'Catégorie active',
                'data' => $category->getIsActive(),
                'required' => false
            ));
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class,
        ));
    }
}