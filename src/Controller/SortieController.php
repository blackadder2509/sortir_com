<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Form\SearchFormType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sorties', name: 'app_sortie_index')]
    public function index(SortieRepository $repository, Request $request): Response
    {
        // 1. On prépare le panier vide
        $data = new SearchData();

        // 2. MODIFICATION : On ne remplit le campus QUE si un utilisateur est connecté
        // (Ça évite le plantage si tu n'es pas connecté)
        if ($this->getUser()) {
            $data->campus = $this->getUser()->getCampus();
        }

        // 3. Création du formulaire
        $form = $this->createForm(SearchFormType::class, $data);

        // 4. Traitement de la requête
        $form->handleRequest($request);

        // 5. Recherche en base de données
        $sorties = $repository->findSearch($data);

        // 6. Affichage
        return $this->render('sortie/index.html.twig', [
            'form' => $form->createView(),
            'sorties' => $sorties,
        ]);
    }
}
