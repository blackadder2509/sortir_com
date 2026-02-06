<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Form\CampusType;
use App\Repository\CampusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CampusController extends AbstractController
{
    #[Route('/campus', name: 'app_campus')]
    public function index(CampusRepository $campusRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1. Création du campus vide et du formulaire
        $campus = new Campus();
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        // 2. Traitement du formulaire d'ajout
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

            // Message flash pour dire "Bravo" (optionnel mais sympa)
            $this->addFlash('success', 'Campus ajouté avec succès !');

            return $this->redirectToRoute('app_campus');
        }

        // 3. Affichage de la vue
        return $this->render('campus/index.html.twig', [
            'campus' => $campusRepository->findAll(),
            'campusForm' => $form->createView(),
        ]);
    }

    #[Route('/campus/modifier/{id}', name: 'app_campus_edit')]
    public function edit(Campus $campus, Request $request, EntityManagerInterface $entityManager): Response
    {
        // 1. Création du formulaire rempli avec le campus existant
        $form = $this->createForm(CampusType::class, $campus);
        $form->handleRequest($request);

        // 2. Traitement de la modification
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush(); // Pas besoin de persist, l'objet existe déjà

            $this->addFlash('success', 'Campus modifié !');

            return $this->redirectToRoute('app_campus');
        }

        // 3. Affichage de la vue de modification
        return $this->render('campus/edit.html.twig', [
            'campusForm' => $form->createView(),
        ]);
    }

    #[Route('/campus/supprimer/{id}', name: 'app_campus_delete')]
    public function delete(Campus $campus, EntityManagerInterface $entityManager): Response
    {
        // 1. On prépare la suppression du campus trouvé
        $entityManager->remove($campus);

        // 2. On valide l'action en base de données
        $entityManager->flush();

        // 3. Petit message de succès
        $this->addFlash('success', 'Campus supprimé avec succès !');

        // 4. Retour à la liste
        return $this->redirectToRoute('app_campus');
    }
}