<?php


namespace App\Controller;

use DateTime;
use Dompdf\Dompdf;
use Dompdf\Options;
use Exception;
use function MongoDB\BSON\toJSON;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Html2Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class ListeCoursesController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 * @Route("/liste_course")
 */
class ListeCoursesController extends AbstractController
{

    /**
     * @Route("/set-{id}", name="listeCourse.set")
     * @param $id
     * @return Response
     */
    public function setListeCourse($id, Request $request): Response
    {
        $filename = $this->getUser()->getUsername().".json";
        $arr_data = array();

        try
        {
            try{
                $jsondata = file_get_contents($filename);
                $arr_data = json_decode($jsondata, true);
            }
            catch (Exception $e){}

            if (is_null($arr_data))
            {
                $arr_data = array();
            }
            $formdata = array(
                'id' => uniqid("", true),
                'titre' => $request->get('_titre'),
                'ingredient' => explode("\r\n",$request->get('_ingredient'), -1)
            );

            array_push($arr_data, $formdata);
            $jsondata = json_encode($arr_data, JSON_PRETTY_PRINT);

            $result = file_put_contents($filename, $jsondata);
            if ($result != false)
            {
                $this->addFlash('courses', 'Liste de courses mise à jour. ');
                return $this->redirectToRoute('recette.show', [
                    'id' => $id,
                ]);
            }
            else
            {
                $this->addFlash('danger', 'Erreur lors de l\'ajout à la liste de courses.');
                return $this->redirectToRoute('recette.show', [
                    'id' => $id,
                ]);
            }
        }
        catch (Exception $e){
            $this->addFlash('danger', $e->getMessage());
            return $this->redirectToRoute('recette.show', [
                'id' => $id,
            ]);
        }
    }

    /**
     * @Route(name="listeCourse.get")
     */
    public function getListeCourse(): Response
    {
        $arr_data = array();
        try{
            $json = file_get_contents($this->getUser()->getUsername() . ".json");
            $arr_data = json_decode($json, true);
        }
        catch (Exception $e) {}

        return $this->render('liste_course/get.html.twig',
            [
                'liste' => $arr_data,
            ]);
    }

    /**
     * @Route("/remove-{id}-{ingredient}", name="listeCourse.remove")
     * @param $id
     * @param string $ingredient
     * @return Response
     */
    public function removeIngredientListeCourse($id, string $ingredient): Response
    {
        $json = file_get_contents($this->getUser()->getUsername() . ".json");

        $json_arr = json_decode($json, true);

        $arr_index = array();
        $ingredient_index = array();
        foreach ($json_arr as $key => $value)
        {
            if ($value['id'] == $id)
            {
                $arr_index[] = $key;
                foreach ($value['ingredient'] as $test => $val)
                {
                    if ($val == $ingredient)
                    {
                        $ingredient_index[] = $test;
                    }
                }
            }
        }

        foreach ($arr_index as $i)
        {
            foreach ($ingredient_index as $j)
            {
                unset($json_arr[$i]['ingredient'][$j]);
                if (sizeof($json_arr[$i]['ingredient']) === 0)
                {
                    unset($json_arr[$i]);
                }
            }
        }


        $json_arr = array_values($json_arr);

        file_put_contents($this->getUser()->getUsername() . ".json", json_encode($json_arr, JSON_PRETTY_PRINT));

        return $this->redirectToRoute('listeCourse.get');
    }

    /**
     * @Route("/remove_recette-{id}", name="listeCourse.remove_recette")
     * @param $id
     * @return Response
     */
    public function removeRecetteListeCourse($id): Response
    {
        $json = file_get_contents($this->getUser()->getUsername() . ".json");

        $json_arr = json_decode($json, true);

        $arr_index = array();
        foreach ($json_arr as $key => $value)
        {
            if ($value['id'] == $id)
            {
                $arr_index[] = $key;
            }
        }

        foreach ($arr_index as $i)
        {
            unset($json_arr[$i]);
        }

        $json_arr = array_values($json_arr);

        file_put_contents($this->getUser()->getUsername() . ".json", json_encode($json_arr, JSON_PRETTY_PRINT));

        return $this->redirectToRoute('listeCourse.get');
    }

    /**
     * @Route("/toPdf", name="toPdf")
     * @return Response
     * @throws Html2PdfException
     * @throws Exception
     */
    public function toPDF(): Response
    {

        $arr_data = array();
        try{
            $json = file_get_contents($this->getUser()->getUsername() . ".json");
            $arr_data = json_decode($json, true);
        }
        catch (Exception $e) {}
        $date = new \DateTime();
        $result = $date->format('Y-m-d');
        $template = $this->renderView('pdf/liste_courses.html.twig',
            [
               'date_ajd' => $result,
               'courses' => $arr_data,
            ]);
/*
        $html2pdf = new Html2Pdf('P', 'A4', 'fr');
        $html2pdf->writeHTML($template);
        $html2pdf->output(); */
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        // Load HTML to Dompdf
        $dompdf->loadHtml($template);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("liste_course.pdf", [
            "Attachment" => false
        ]);
        return $this->redirectToRoute('listeCourse.get');
    }
}