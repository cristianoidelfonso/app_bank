<?php

namespace App\Form;

use App\Entity\Agencia;
use App\Entity\Banco;
use App\Entity\Conta;
use App\Repository\AgenciaRepository;
use App\Repository\BancoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContaType extends AbstractType
{
    public function __construct(
        private BancoRepository $bancoRepository,
        private AgenciaRepository $agenciaRepository
    ){

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $agencias = $this->agenciaRepository->findAll();
        $num = (rand(1111111, 9999999) .'-'.rand(11, 99));
        
        $builder
            ->add('numero', TextType::class,[
                'label' => 'Número', 
                'attr'=> ['readonly' => true, 'value' => $num],   
            ])
            ->add('agencia', ChoiceType::class, [
                'label' => 'Agência',
                'choices'=> $agencias,
                'choice_label' => function (?Agencia $agencia) {
                    return $agencia ? strtoupper($agencia->getNome() .' - ' .$agencia->getBanco()->getNome()) : '';
                }, 
                'choice_value' => function (?Agencia $entity) {
                    return $entity ? $entity->getId() : '';
                },      
            ])
            // ->add('created_at')
            // ->add('updated_at')
        ;


        // $builder
        //     // ->add('numero')
        //     ->add('banco', EntityType::class, [
        //         'class' => Banco::class,
        //         'choice_label' => function (?Banco $banco) {return $banco ? strtoupper($banco->getNome()) : ''; },
        //         'placeholder' => '',
        //     ])
        // ;

        // === Início do bloco ======================================
        // $formModifier = function (FormInterface $form, Agencia $agencia = null) {
        //     $bancos = null === $agencia ? [] : $agencia->getNome();
        //     dump($bancos);
        //     $form->add('banco', EntityType::class, [
        //         'class' => Banco::class,
        //         'placeholder' => '',
        //         'choices' => $bancos,
        //         'choice_label' => function (?Banco $banco) {return $banco ? strtoupper($banco->getNome()) : ''; },
        //     ]);
        // };

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) use ($formModifier) {
        //         $data = $event->getData();
        //         dump($data);
        //         $formModifier($event->getForm(), $data->getAgencia());
        //     }
        // );

        // $builder->get('agencia')->addEventListener(
        //     FormEvents::POST_SUBMIT,
        //     function (FormEvent $event) use ($formModifier) {
        //         $agencia = $event->getForm()->getData();
        //         $formModifier($event->getForm()->getParent(), $agencia);
        //     }
        // );
        // === Fim do bloco ======================================
        
        // === Inicio do bloco ======================================
        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA, // PRE_SET_DATA, POST_SET_DATA, PRE_SUBMIT, SUBMIT, POST_SUBMIT
        //     function (FormEvent $event) {
        //         $form = $event->getForm();
        //         $data = $event->getData();
        //         dump($form);
        //         dump($data);

        //         $banco = $data->getAgencia();
        //         dump($banco);
        //         $agencias =  $banco !== null ? $this->bancoRepository->findAll() : [];
        //         dump($agencias);

        //         $form->add('agencia', EntityType::class, [
        //             'class' => Agencia::class,
        //             'placeholder' => '',
        //             'choices' => $agencias,
        //             // 'choice_label' => function (?Agencia $agencia) {return $agencia ? strtoupper($agencia->getNome()) : ''; },
        //         ]);
        //     }
        // );
        // === Fim do bloco ======================================
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conta::class,
        ]);
    }
}
