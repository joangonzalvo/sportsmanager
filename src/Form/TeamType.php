<?php

namespace App\Form;

   use Symfony\Component\Form\AbstractType;
   use Symfony\Component\Form\FormBuilderInterface;
   use Symfony\Component\Form\Extension\Core\Type\TextType;
   use Symfony\Component\Form\Extension\Core\Type\FileType;
   use Symfony\Component\Form\Extension\Core\Type\SubmitType;
   use Symfony\Component\OptionsResolver\OptionsResolver;
   
class TeamType extends AbstractType{
    public function buildForm(FormBuilderInterface $builder, array $options) {
            $builder
                    ->add('teamname', TextType::class,[
                        'label'=>'Team name',
                        'required'=>'required',
                        'attr'=>[
                            'class'=>'form-teamname form-control'
                        ]
                    ])
                    ->add('logo',FileType::class,[
                    	'required'=>true,
                    	'label'=>'Your team logo (PNG,JPEG)'
                	])
                    ->add('Signup', SubmitType::class,
                            ['label'=>'Register team',
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
