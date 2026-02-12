<?php
namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SearchType;
use App\Model\SearchData;
use App\Repository\SortieRepository;
use App\Service\SortieManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'app_sortie_')]
class SortieController extends AbstractController
{
    #[Route('s', name: 'index')]
    public function index(SortieRepository $repo, SortieManager $sm, Request $request): Response
    {
        $sm->updateEtats();
        $searchData = new SearchData();
        $form = $this->createForm(SearchType::class, $searchData);
        $form->handleRequest($request);

        return $this->render('sortie/index.html.twig', [
            'sorties' => $repo->findSearch($searchData, $this->getUser()),
            'form' => $form->createView()
        ]);
    }
}
