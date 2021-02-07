<?php

namespace App\Form;

use App\Entity\Event;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


class EventType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,
             ['label' => 'Full Name']
             )
            ->add('description', TextType::class, ['label' => 'Full Name'])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Event Date'
            ])
            ->add('start_time', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'Start Time'
            ])
            ->add('end_time', TimeType::class, [
                'widget' => 'single_text',
                'label' => 'End Time',
                'constraints' => [
                    new NotBlank(),
                    new DateTime(),
                    new Callback(function($object, ExecutionContextInterface $context) {
                        $obj = $context->getRoot()->getData();
                        $start = $obj->getStartTime();
                        $stop = $object;
                        if (is_a($start, \DateTime::class) && is_a($stop, \DateTime::class)) {
                            if ($stop->format('U') - $start->format('U') < 0) {
                                $context
                                    ->buildViolation('Stop time must be after start time')
                                    ->addViolation();
                            }
                        }
                    }),
                ]
            ])
            ->add('seats', NumberType::class,
             ['label' => 'Seats'],
             ['attr' => ['min' => 5, 'max' => 100]]
             )
            ->add('save', SubmitType::class, ['label' => 'Register new Event'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
