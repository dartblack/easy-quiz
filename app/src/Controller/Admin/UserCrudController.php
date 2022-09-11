<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserCrudController extends AbstractCrudController
{

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->showEntityActionsInlined()
            ->setSearchFields(['email', 'id', 'roles'])
            ->setDefaultSort(['id' => 'ASC']);
    }


    public function configureFields(string $pageName): iterable
    {
        $emailField = EmailField::new('email');
        $passwordField = Field::new('password', 'New password')->setRequired(true)
            ->setFormType(RepeatedType::class)
            ->setFormTypeOptions([
                'type' => PasswordType::class,
                'first_options' => ['label' => 'New password'],
                'second_options' => ['label' => 'Repeat password'],
                'error_bubbling' => true,
                'invalid_message' => 'The password fields do not match.',
            ])->onlyOnForms();

        if ($pageName == Crud::PAGE_EDIT) {
            $emailField->setDisabled();
            $passwordField->setRequired(false);
        }

        yield FormField::addPanel('User data')->setIcon('fa fa-user');
        yield IdField::new('id')->onlyOnIndex();
        yield $emailField;


        yield ChoiceField::new('roles')
            ->setChoices(User::USER_ROLES)
            ->allowMultipleChoices()
            ->renderAsBadges();
        yield FormField::addPanel('Manage password')->setIcon('fa fa-key');
        yield $passwordField;

    }

}
