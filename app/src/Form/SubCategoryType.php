<?php
namespace App\Form;

use App\Entity\SubCategory;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SubCategoryType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $subCategory = $options["data"];
        $builder
            ->add('label', TextType::class)
            ->add('description', TextType::class)
            ->add('isActive', CheckBoxType::class, array(
                'label' => 'Sous-Catégorie active',
                'data' => $subCategory->getIsActive(),
                'required' => false
            ))
            ->add('category', EntityType::class, array(
                'class' => Category::class,
                'choice_label' => 'label'
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => SubCategory::class,
        ));
    }
}