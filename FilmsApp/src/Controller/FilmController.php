<?php

namespace App\Controller;

use App\Entity\Film;
use App\Entity\Genre;
use App\Form\FilmType;
use App\Services\ImageService;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;



class FilmController extends AbstractController
{

    /**
     * @Route("/", name="index")
     */
    public function index(){
        return $this->redirectToRoute("home");
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
     * @Route("/delete/{id}", name="delete")
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(int $id){
        $em = $this->getDoctrine()->getManager();
        $film = $em->getRepository(Film::class)->find($id);

        $em->remove($film);
        $em->flush();

        $this->addFlash('success', 'Film removed.');
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/getImage/{id}", name="getImage")
     * @param int $id The film primary key.
     * @param ImageService $imageService
     */
    public function retrieveImage(int $id, ImageService $imageService)
    {
        $film = $this->getDoctrine()
                    ->getRepository(Film::class)
                    ->find($id);


        $imageService->showImage($film->getCover(), $film->getCoverType());
    }

    /**
     * @Route("/add_film", name="add_film", methods={"GET","POST"})
     * @param Request $request
     * @param ImageService $imageService
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request, ImageService $imageService){

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
            $filmCover = $form->get('cover')->getData();

            if ($filmCover){
                $fileData = $imageService->saveUploadedImage($filmCover);
                $film->setCover($fileData['filename']);
                $film->setCoverType($fileData['type']);
            }

            $filmGenre = $this->getDoctrine()
                ->getRepository(Genre::class)
                ->find($form->get('genre')->getData());

            $film->setGenre($filmGenre);

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
