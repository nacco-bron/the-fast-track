<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Repository\CommentRepository;
use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class ConferenceController extends AbstractController
{
    /** @var Environment $twig */
    private $twig;

    /** @var CommentRepository $commentRepository */
    private $commentRepository;

    /** @var ConferenceRepository $conferenceRepository */
    private $conferenceRepository;

    /**
     * @param Environment $twig
     * @param CommentRepository $commentRepository
     * @param ConferenceRepository $conferenceRepository
     */
    public function __construct(
        Environment $twig,
        CommentRepository $commentRepository,
        ConferenceRepository $conferenceRepository
    ) {
        $this->twig = $twig;
        $this->commentRepository = $commentRepository;
        $this->conferenceRepository = $conferenceRepository;
    }

    #[Route('/', name: 'homepage')]
    public function index(): Response
    {
        return new Response($this->twig->render('conference/index.html.twig', [
            'conferences' => $this->conferenceRepository->findAll(),
        ]));
    }


    #[Route('/conference/{id}', name: 'conference', requirements: ['id' => '\d+'])]
    public function show(Conference $id): Response
    {
        return new Response($this->twig->render('conference/show.html.twig', [
            'conference' => $id,
            'comments' => $this->commentRepository->findBy(['conference' =>
            $id], ['createdAt' => 'DESC']),
        ]));
    }
}
