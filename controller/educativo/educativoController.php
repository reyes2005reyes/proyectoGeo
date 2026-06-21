<?php

class EducativoController
{

    public function catalogo()
    {
        include_once dirname(__FILE__) . '/../../view/educativo/catalogo.php';
    }


    public function reglamentarias()
    {
        $reglamentarias = require dirname(__FILE__) . '/data_reglamentarias.php';

        include_once dirname(__FILE__) . '/../../view/educativo/reglamentarias.php';
    }


    public function preventivas()
    {
        $preventivas = require dirname(__FILE__) . '/data_preventivas.php';

        include_once dirname(__FILE__) . '/../../view/educativo/preventivas.php';
    }


    public function informativas()
    {
        $informativas = require dirname(__FILE__) . '/data_informativas.php';

        include_once dirname(__FILE__) . '/../../view/educativo/informativas.php';
    }


    public function reductores()
    {
        $reductores = require dirname(__FILE__) . '/data_reductores.php';

        include_once dirname(__FILE__) . '/../../view/educativo/reductores.php';
    }



    public function quiz()
    {
        $preguntas = require dirname(__FILE__) . '/data_quiz.php';


        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {

            $respuestas = $_POST['respuesta'];

            $puntos = 0;


            foreach($preguntas as $key => $pregunta)
            {

                if(isset($respuestas[$key]))
                {

                    if($respuestas[$key] == $pregunta['correcta'])
                    {
                        $puntos++;
                    }

                }

            }


            $total = count($preguntas);


            $calificacion = array(
                'puntos'=>$puntos,
                'total'=>$total,
                'porcentaje'=>($puntos * 100) / $total
            );


            include_once dirname(__FILE__) . '/../../view/educativo/resultadoQuiz.php';

            return;

        }



        shuffle($preguntas);

        $preguntas = array_slice($preguntas,0,10);


        include_once dirname(__FILE__) . '/../../view/educativo/quiz.php';
    }


}

?>