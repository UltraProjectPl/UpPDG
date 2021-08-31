<?php
declare(strict_types=1);

namespace App\Employer\Infrastructure\SymfonyIntegration\Form\Type;

use App\Employer\Application\Form\Dto\OfferDto;
use App\Employer\Application\Form\Type\OfferFormInterface;
use App\Employer\Infrastructure\SymfonyIntegration\Validation\UniqueTitlePerUser;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class OfferForm extends AbstractType implements OfferFormInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                    new UniqueTitlePerUser()
                ],
            ])
            ->add('companyName', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('paymentSpreads', PaymentSpreadsType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('remoteWorkPossible', CheckboxType::class)
            ->add('remoteWorkOnly', CheckboxType::class)
            ->add('disabled', CheckboxType::class)
            // @todo validation GUS
            ->add('nip', IntegerType::class, [
                'required' => false,
            ])
            ->add('tin', TextType::class, [
                'constraints' => [
                    new Length(['max' => 255]),
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OfferDto::class,
            'csrf_protection' => false,
            'method' => Request::METHOD_POST,
        ]);
    }
}