<?php

namespace App\Form;


use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Request\QuizSessionQuestionRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function __construct(private readonly QuestionRepository $questionRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var QuizSessionQuestionRequest $formRequest */
        $formRequest = $options['data'];
        $question = $this->questionRepository->find($formRequest->getQuestionId());
        $choices = [];
        if ($question->getMode() == Question::MODE_CHOICE) {
            $answers = $question->getAnswers();
            foreach ($answers as $answer) {
                $choices[$answer->getText()] = $answer->getId();
            }
            $temp = [];
            $keys = array_keys($choices);
            shuffle($keys);
            foreach ($keys as $key) {
                $temp[$key] = $choices[$key];
            }
            $choices = $temp;
        } else {
            $choices = ['True' => 1, 'False' => 0];
        }

        $builder
            ->add('uuid', HiddenType::class)
            ->add('questionId', HiddenType::class)
            ->add('answer', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => $choices,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuizSessionQuestionRequest::class,
        ]);
    }
}