<?php

namespace App\Controller;

use App\Entity\Photo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Form\CropperType;
use Symfony\UX\Cropperjs\Factory\CropperInterface;

class CropperController extends AbstractController
{
    /**
     * @Route("/crop/{id}", name="app_crop_image")
     */
    public function index(CropperInterface $cropper, Request $request, Photo $photo): Response
    {

        $crop = $cropper->createCrop($this->getParameter('kernel.project_dir').'/public/photo/'.$photo->getImageName());
        $crop->setCroppedMaxSize(800, 800);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => '/photo/'.$photo->getImageName(),
                'view_mode' => 0,
                'drag_mode' => 'crop',
                //'initial_aspect_ratio' => 1900 / 2000,
                'aspect_ratio' => 1900 / 2000,
                'responsive' => true,
                'restore' => true,
                'check_cross_origin' => true,
                'check_orientation' => false,
                'modal' => true,
                'guides' => true,
                'center' => true,
                'highlight' => true,
                'background' => true,
                'auto_crop_area' => 0.99,
                'zoomable' => true,
                'zoom_on_touch' => true,
                'zoom_on_wheel' => true,
                'wheel_zoom_ratio' => 0.1,
                'crop_box_movable' => true,
                'crop_box_resizable' => false,
                'toggle_drag_mode_on_dblclick' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Suivant",
                'attr' => [
                    'class' => 'btn btn-lg btn-primary',
                ],
                ])
            ->getForm()
        ;

        //Matrix ou Alice aux pays des Merveilles ?

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the cropped image data (as a string)
            //$crop->getCroppedImage();

            // Create a thumbnail of the cropped image (as a string)
            //$crop->getCroppedThumbnail(200, 150);
            file_put_contents($this->getParameter('kernel.project_dir').'/public/photo/'.$photo->getImageName(), $crop->getCroppedImage());

            return $this->redirectToRoute('home');
            // ...
        }

        return $this->render('ajouter/crop.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}