<?php

namespace App\Controller;

use App\Entity\Location;
use App\Repository\LocationRepository;
use App\Service\PdfService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractController
{
    #[Route('/download/{id}', name: 'download')]
    public function generatePDF(Location $location, PdfService $pdfService): Response
    {

        // $arrContextOptions=array(
        //     "ssl"=>array(
        //         "verify_peer"=>false,
        //         "verify_peer_name"=>false,
        //     ),
        // );  

    //    $img_path = 'http://127.0.0.1:8000/images/modele2';
    //     $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
    //     $data = file_get_contents($img_path, false);
    //     $img_base_64 = base64_encode($data);
    //     $path_img = 'data:image/' . $extencion . ';base64,' . $img_base_64;



        return $pdfService->renderPDF(
            "upload/pdf.html.twig",
            [
                'location' => $location,
              //  'path_image' => $path_img
            ],
            "reservation.pdf"
        );
    }
}
