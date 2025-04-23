<?php

namespace App\Controller\Professional;

use App\Entity\CleaningRequest;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use Symfony\Bundle\SecurityBundle\Security;

class AcceptedCleaningRequestCrudController extends AbstractCrudController
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    ) {}

    public static function getEntityFqcn(): string
    {
        return CleaningRequest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Demande acceptée')
            ->setEntityLabelInPlural('Demandes acceptées')
            ->setDefaultSort(['date' => 'DESC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('date', 'Date'),
            DateTimeField::new('startTime', 'Heure de début'),
            DateTimeField::new('endTime', 'Heure de fin'),
            TextField::new('client.firstname', 'Prénom du client'),
            TextField::new('client.lastname', 'Nom du client'),
            TextField::new('client.address', 'Adresse du client'),
            TextField::new('client.phone', 'Téléphone du client'),
            TextEditorField::new('description', 'Description'),
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): \Doctrine\ORM\QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        $user = $this->security->getUser();
        $professional = $user ? $user->getProfessional() : null;

        $qb->andWhere('entity.isAccepted = true');
        $qb->andWhere('entity.professional = :professional')
            ->setParameter('professional', $professional);

        return $qb;
    }
}
