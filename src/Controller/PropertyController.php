<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController {

  /**
   * @var PropertyRepository
   */
  private $repository;

  /**
   * PropertyController constructor.
   * @param PropertyRepository $repository
   */
  public function __construct(PropertyRepository $repository) {
    $this->repository = $repository;
  }

  /**
   * @param string $title
   * @param string $description
   * @param int $surface
   * @param int $postalCode
   * @param bool $sold
   * @param string $address
   * @param string $city
   * @param int $price
   */
  public function addProperty(string $title, string $description, int $surface, int $postalCode, bool $sold, string $address, string $city, int $price) {
    $property = new Property();
    $property->setTitle($title)
      ->setDescription($description)
      ->setSurface($surface)
      ->setPostalCode($postalCode)
      ->setSold($sold)
      ->setAddress($address)
      ->setCity($city)
      ->setPrice($price);

    $em = $this->getDoctrine()->getManager();
    $em->persist($property);
    $em->flush();
  }

  /**
   * @Route("/biens", name="property.index")
   * @return Response
   */
  public function index(): Response {
    // Get properties
    $properties = $this->repository->findAllSoldable();
    dump($properties);

    // If empty create properties
    if (empty($properties)) {
      // Create element into my table
      $this->addProperty('Super appart', 'Appart plutôt cool', 60, 86220, false, '20 rue de je sais pas quoi', 'Je sais pas où', 700);
      $this->addProperty('Autre super appart', 'Appart plutôt cool lui aussi', 70, 17000, false, '10 rue de lalala', 'La Rochelle', 800);
      $this->addProperty('Un appartement', 'Appartement où il fait bon vivre', 30, 44000, false, '14 Avenue pouet', 'Nantes', 550);
      $this->addProperty('Appartement plutôt bof', 'Franchement... n achetez pas', 20, 86220, false, 'au beau mileu de nul part', 'Campagne', 1200);

      $properties = $this->repository->findAllSoldable();
      dump($properties);
    }

    // Get element into my table
    //$repository = $this->getDoctrine()->getRepository(Property::class);
    //dump($repository);

    return $this->render('property/index.html.twig', [
      'properties' => $properties
    ]);
  }

  /**
   * @param string $slug
   * @param string $id
   * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
   * @return Response
   */
  public function show(string $slug, string $id): Response {
    dump($slug);
    dump($id);
    $property = $this->repository->find($id);

    return $this->render('property/show.html.twig', [
      'current_menu' => 'properties',
      'property' => $property
    ]);
  }

}