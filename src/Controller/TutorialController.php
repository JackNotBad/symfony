<?php

namespace App\Controller;

use App\Enum\TutorialStatus;
use App\Entity\Tutorial;
use App\Repository\TutorialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use function Symfony\Component\Clock\now;

final class TutorialController extends AbstractController
{
    #[Route('/tutorial/{id}', name: 'app_tutorial')]
    // 1iere façon de faire
    // public function index(EntityManagerInterface $entityManager, int $id): Response
    public function index(TutorialRepository $tutorialRepository, int $id): Response
    {
        // 1iere façon de faire
        // $tutorial = $entityManager->getRepository(Tutorial::class)->find($id);
        $tutorial = $tutorialRepository->findOneById($id);

        if (!$tutorial) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return $this->render('tutorial/index.html.twig', [
            'controller_name' => 'TutorialController',
            'title' => $tutorial->getTitle()
        ]);
    }


    #[Route('/add-tutorial', name: 'create_tutorial')]
    public function createTutorial(EntityManagerInterface $entityManager): Response
    {
        $tutorial = new Tutorial();
        $tutorial->setTitle("Étapes minimales à faire pour l'UML");
        $tutorial->setslug('tuto-uml');
        $tutorial->setcontent('	
    • Acteurs + cas d’utilisation (diagramme + 1 fiche par cas prioritaire).
	• 1–2 diagrammes d’activité pour les parcours critiques.
	• 2 diagrammes de séquence (auth + flux business clé).
	• Diagramme de classes du domaine (entités majeures).
	• Diagramme de composants (architecture) + déploiement simple.
 	• 1 page NFR (perf, sécu, RGPD) + 1 page tests d’acceptation.
    ');
        $tutorial->setstatus(TutorialStatus::DRAFT);
        $tutorial->setcreationDate(now());
        $tutorial->setmodificationDate(new \DateTime('now'));
        $tutorial->setpublicationDate(null);

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($tutorial);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$tutorial->getId());
    }
}
