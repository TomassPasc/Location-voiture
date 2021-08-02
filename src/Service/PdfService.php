<?php

namespace App\Service;


use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;
use Symfony\Component\HttpFoundation\Response;


class PdfService
{

    private $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }


    public function renderPDF($template, $params, $filename): Response
    {
        define("DOMPDF_UNICODE_ENABLED", true);
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFront', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);
        $dompdf = new Dompdf($pdfOptions);

        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        $html = $this->twig->render($template, $params);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $fichier = $filename;
        $dompdf->stream($fichier, [
            'Attachement' => true
        ]);
        return new Response();
    }
}
