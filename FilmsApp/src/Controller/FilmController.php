<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Form\FilmType;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{


    /**
     * @Route("/film", name="film")
     */
    public function index()
    {
        return $this->render('film/index.html.twig', [
            'controller_name' => 'FilmController',
        ]);
    }

    /**
     * @Route("/home", name="home")
     */
    public function home(){

        $films = $this->getDoctrine()
                        ->getRepository(Film::class)
                        ->findAll();

        return $this->render('home.html.twig', [
            'films'=>$films,
        ]);
    }

    /**
     * @Route("/getImage/{id}", name="getImage")
     * @param int $id The film primary key.
     */
    public function retrieveImage(int $id)
    {
        $film = $this->getDoctrine()
                    ->getRepository(Film::class)
                    ->find($id);


        $imgService = \ImageService::getInstance();
        $imgService->showImage($film->getCoverType(), stream_get_contents($film->getCover()));
    }

    /**
     * @Route("/add_film", name="add_film", methods={"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request){

        $film = new Film();

        $allGenres = $this->getDoctrine()
                            ->getRepository(Genre::class)
                            ->findAll();

        $genreList = [];
        foreach($allGenres as $genre){
            $genreList[$genre->getName()] = $genre->getId();
        }

        $form = $this->createForm(FilmType::class, $film, ['genre_options'=>$genreList]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $film->setIdGenre($form->get('genre')->getData());
            $film->setCoverType(\ImageService::getInstance()->getImageType($film->getCover()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($film);
            $entityManager->flush();

            $this->addFlash('success', 'Film added successfully!');
            return $this->redirectToRoute('home');
        }

        return $this->render('add_film_form.html.twig',
                            ['form'=> $form->createView()]);
    }
}
