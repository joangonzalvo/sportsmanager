<?php

namespace App\Form;

   use Symfony\Component\Form\AbstractType;
   use Symfony\Component\Form\FormBuilderInterface;
   use Symfony\Component\Form\Extension\Core\Type\TextType;
   use Symfony\Component\Form\Extension\Core\Type\SubmitType;
   use Symfony\Component\OptionsResolver\OptionsResolver;
   
class LeagueType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                    ->add('name', TextType::class,[
                        'label'=>'League name',
                        'required'=>'required',
                        'attr'=>[
                            'class'=>'form-name form-control'
                        ]
                    ])
                    ->add('type', TextType::class,[
                        'label'=>'Type of competition (football, basketball, e-sports...)',
                        'required'=>'required',
                        'attr'=>[
                            'class'=>'form-name form-control'
                        ]
                    ])
                    ->add('Signup', SubmitType::class,
                            ['label'=>'Create new league',
                                'attr'=>[
                                'class'=>'form-submit btn btn-success'
                        ]]);
           
       }
       public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' =>'App\Entity\League',
            ]);
        }
    
    
}
