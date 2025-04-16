<?php

namespace App\Controller\Professional;

use App\Entity\Professional;
use App\Entity\User;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class ProfessionalCrudController extends AbstractCrudController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public static function getEntityFqcn(): string
    {
        return Professional::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('firstname'),
            TextField::new('lastname'),
            TextField::new('phone'),
            TextField::new('city'),
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, 
        FieldCollection $fields, FilterCollection $filters): QueryBuilder {
        
        $user = $this->security->getUser();

        return parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters)
            ->andWhere('entity.user = :user')
            ->setParameter('user', $user);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = parent::configureActions($actions);

        /**
         * @var User $user
         */

        
        $user = $this->security->getUser();
        if ($user && $user->getProfessional()) {
            $actions->disable('new');
        }
        
        // Désactiver l'action de suppression partout
        $actions->disable('delete');

        // Désactiver le bouton "Create and add another"
        $actions->remove(Crud::PAGE_NEW, Action::SAVE_AND_ADD_ANOTHER);

        return $actions;
    }

    public function persistEntity($entityManager, $entityInstance): void
    {

        /**
         * @var User $user
         */
        $user = $this->security->getUser();
        
        // Vérifier si l'utilisateur a déjà un profil professionnel
        if ($user->getProfessional()) {
            throw new \RuntimeException('Vous avez déjà créé un profil professionnel.');
        }

        
        
        $entityInstance->setUser($user);
        parent::persistEntity($entityManager, $entityInstance);
    }

    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        $user = $entityInstance->getUser();
        $user->setUpdatedAt(new \DateTimeImmutable());
        
        parent::updateEntity($entityManager, $entityInstance);
    }
}
