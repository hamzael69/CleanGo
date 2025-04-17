<?php

namespace App\Controller\Professional;

use App\Entity\CleaningRequest;
use App\Entity\Professional;
use App\Repository\CleaningRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\SecurityBundle\Security;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class CleaningRequestCrudController extends AbstractCrudController
{
    public function __construct(
        private Security $security,
        private AdminUrlGenerator $adminUrlGenerator,
        private EntityManagerInterface $entityManager
    ) {}

    public static function getEntityFqcn(): string
    {
        return CleaningRequest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Demande de ménage')
            ->setEntityLabelInPlural('Demandes de ménage')
            ->setDefaultSort(['date' => 'ASC']);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            DateTimeField::new('date', 'Date'),
            DateTimeField::new('startTime', 'Heure de début'),
            DateTimeField::new('endTime', 'Heure de fin'),
            AssociationField::new('client', 'Client'),
            AssociationField::new('service', 'Services'),
            TextEditorField::new('description', 'Description')
            // BooleanField::new('isAccepted', 'Acceptée')
        ];
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): \Doctrine\ORM\QueryBuilder
    {
        $qb = parent::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);
        $qb->andWhere('entity.isAccepted = false');
        $qb->andWhere('entity.professional IS NULL');

        return $qb;
    }

    public function configureActions(Actions $actions): Actions
    {
        $accept = Action::new('accept', 'Accepter', 'fa fa-check')
            ->linkToCrudAction('acceptRequest')
            ->setCssClass('btn btn-success')
            ->displayIf(fn ($entity) => !$entity->isAccepted());

        return $actions
            ->add(Crud::PAGE_INDEX, $accept)
            ->remove(Crud::PAGE_INDEX, Action::DELETE)
            ->remove(Crud::PAGE_INDEX, Action::EDIT)
            ->remove(Crud::PAGE_INDEX, Action::NEW);
    }

    public function acceptRequest(
        AdminContext $context,
        CleaningRequestRepository $cleaningRequestRepository
    ): Response {    

        /** @var CleaningRequest|null $cleaningRequest */
        $cleaningRequest = $cleaningRequestRepository->find($context->getRequest()->query->get('entityId'));

        if (!$cleaningRequest instanceof CleaningRequest) {
            $this->addFlash('danger', 'Demande de ménage introuvable.');
            return $this->redirect($context->getReferrer());
        }

        /** @var Professional|null $professional */
        $professional = $this->security->getUser()?->getProfessional();

        if (!$professional instanceof Professional) {
            $this->addFlash('danger', 'Vous devez être connecté en tant que professionnel.');
            return $this->redirect($context->getReferrer());
        }

        if ($cleaningRequest->isAccepted()) {
            $this->addFlash('danger', 'Cette demande a déjà été acceptée.');
            return $this->redirect($context->getReferrer());
        }

        try {
            // Démarrer une transaction
            $this->entityManager->beginTransaction();

            // Mettre à jour la demande
            $cleaningRequest->setIsAccepted(true);
            $cleaningRequest->setProfessional($professional);
            $this->entityManager->persist($cleaningRequest);

            // Ajouter la demande au professionnel
            $professional->addCleaningRequest($cleaningRequest);
            $this->entityManager->persist($professional);

            // Sauvegarder les changements
            $this->entityManager->flush();

            // Valider la transaction
            $this->entityManager->commit();

            $this->addFlash('success', 'Demande acceptée avec succès !');
        } catch (\Exception $e) {
            // En cas d'erreur, annuler la transaction
            $this->entityManager->rollback();
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'acceptation de la demande.');
        }

        $url = $this->adminUrlGenerator
            ->setController(self::class)
            ->setAction('index')
            ->generateUrl();

        return $this->redirect($url);
    }
}
