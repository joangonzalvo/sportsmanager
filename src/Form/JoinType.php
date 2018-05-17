<?php

namespace App\Form;

   use Symfony\Component\Form\AbstractType;
   use Symfony\Component\Form\FormBuilderInterface;
   use Symfony\Component\Form\Extension\Core\Type\TextType;
   use Symfony\Component\Form\Extension\Core\Type\FileType;
   use Symfony\Component\Form\Extension\Core\Type\SubmitType;
   use Symfony\Component\OptionsResolver\OptionsResolver;
   
class JoinType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                    ->add('teamkey', TextType::class,[
                        'label'=>'You need to enter a team key in order to join',
                        'required'=>'required',
                        'attr'=>[
                            'class'=>'form-teamkey form-control'
                        ]
                    ])
                    ->add('Signup', SubmitType::class,
                            ['label'=>'Join team',
                                'attr'=>[
                                'class'=>'form-submit btn btn-success'
                        ]]);
           
       }
       public function configureOptions(OptionsResolver $resolver)
        {
            $resolver->setDefaults([
                'data_class' =>'App\Entity\Team',
            ]);
        }
    
    
}