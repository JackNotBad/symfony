<?php

namespace App\Controller\Admin;

use App\Entity\Tutorial;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use App\Entity\Tutoriel;
use App\Entity\User;
use App\Enum\TutorialStatus;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class TutorialCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tutorial::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
    public function configureFields(string $pageName): iterable
    {
            return [
            IdField::new('id')
                ->hideOnForm(),

            TextField::new('title')
                ->setLabel('Titre')
                ->setColumns(6),

            // Solution 1: Utilisation de .value pour l'affichage
            TextField::new('status.value', 'Statut')
                ->onlyOnIndex()
                ->setLabel('Statut'),


            // Pour l'édition/création: ChoiceField avec les valeurs enum
            ChoiceField::new('status')
                ->setLabel('Statut')
                ->onlyOnForms()
                ->setChoices([
                    'Brouillon' => TutorialStatus::DRAFT,
                    'Programmé' => TutorialStatus::SCHEDULED,
                    'Publié' => TutorialStatus::PUBLISHED,
                    'Archivé' => TutorialStatus::ARCHIVED,
                ])
                ->renderAsBadges([
                    TutorialStatus::DRAFT->value => 'secondary',
                    TutorialStatus::SCHEDULED->value => 'warning',
                    TutorialStatus::PUBLISHED->value => 'success',
                    TutorialStatus::ARCHIVED->value => 'dark',
                ])
                ->setFormTypeOption('choice_label', fn($choice) => $choice->value),

            SlugField::new('slug')
                ->setLabel('Slug')
                ->setTargetFieldName('title')
                ->hideOnIndex()
                ->setColumns(6),

            TextareaField::new('content')
                ->setLabel('Contenu (Markdown)')
                ->onlyOnForms()
                ->setNumOfRows(15)
                ->setHelp('Utilisez la syntaxe Markdown pour formater votre contenu'),

            DateTimeField::new('creationDate')
                ->setLabel('Créé le')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->onlyOnIndex(),

            DateTimeField::new('modificationDate')
                ->setLabel('Modifié le')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->onlyOnDetail(),

            DateTimeField::new('publicationDate')
                ->setLabel('Publié le')
                ->setFormat('dd/MM/yyyy HH:mm')
                ->onlyWhenCreating()
                ->onlyWhenUpdating()
                ->setColumns(6),
        ];
    }
}

