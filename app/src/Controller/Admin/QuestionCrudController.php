<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use EasyCorp\Bundle\EasyAdminBundle\Config\Asset;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class QuestionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Question::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setSearchFields(['text', 'id', 'mode'])
            ->setDefaultSort(['id' => 'ASC']);
    }

    public function configureAssets(Assets $assets): Assets
    {
        return $assets
            ->addWebpackEncoreEntry('question');
    }


    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Question Info')->setIcon('fa fa-circle-question');
        yield IdField::new('id')->onlyOnIndex();
        yield ChoiceField::new('mode')
            ->setChoices(Question::QUESTION_MODES);
        yield BooleanField::new('correct')->onlyOnForms()->addCssClass('binary-correct');
        yield TextEditorField::new('text');


        yield FormField::addPanel('Answers')->setCssClass('answers-panel');
        yield CollectionField::new('answers')
            ->useEntryCrudForm(AnswerCrudController::class)
            ->allowAdd()
            ->allowDelete()
            ->onlyOnForms();

    }

}
