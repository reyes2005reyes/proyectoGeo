<?php

class EducativoController
{
    // Método para mostrar la vista del catálogo
    public function catalogo()
    {
        include_once dirname(__FILE__) . '/../../view/educativo/catalogo.php';
    }

    // Método para mostrar la vista de reglamentarias
    public function reglamentarias()
    {
        $reglamentarias = require dirname(__FILE__) . '/data_reglamentarias.php';

        include_once dirname(__FILE__) . '/../../view/educativo/reglamentarias.php';
    }

    // Método para mostrar la vista de preventivas
    public function preventivas()
    {
        $preventivas = require dirname(__FILE__) . '/data_preventivas.php';

        include_once dirname(__FILE__) . '/../../view/educativo/preventivas.php';
    }

    // Método para mostrar la vista de informativas
    public function informativas()
    {
        $informativas = require dirname(__FILE__) . '/data_informativas.php';

        include_once dirname(__FILE__) . '/../../view/educativo/informativas.php';
    }

    // Método para mostrar la vista de reductores
    public function reductores()
    {
        $reductores = require dirname(__FILE__) . '/data_reductores.php';

        include_once dirname(__FILE__) . '/../../view/educativo/reductores.php';
    }


    // Método para mostrar la vista del quiz
    public function quiz()
    {
        // Verificar si es una solicitud POST para calificar el quiz
        if($_SERVER['REQUEST_METHOD'] == 'POST')
        {
            // Verificar si las preguntas están en la sesión
            if (!isset($_SESSION['quiz_preguntas'])) {
                redirect(getUrl('educativo', 'educativo', 'quiz'));
                return;
            }
            // Calificar el quiz
            $preguntas = $_SESSION['quiz_preguntas'];
            $respuestas = $_POST['respuesta'];
            $puntos = 0;
            foreach($preguntas as $key => $pregunta)
            {
                // Verificar si la respuesta del usuario coincide con la respuesta correcta
                if(isset($respuestas[$key]))
                {
                    if($respuestas[$key] == $pregunta['correcta'])
                    {
                        $puntos++;
                    }
                }
            }

            // Calcular el total de preguntas y el porcentaje de aciertos
            $total = count($preguntas);
            $calificacion = array(
                'puntos'=>$puntos,
                'total'=>$total,
                'porcentaje'=>($puntos * 100) / $total
            );


            // Limpiar la sesión para que el siguiente intento sea fresco
            unset($_SESSION['quiz_preguntas']);
            include_once dirname(__FILE__) . '/../../view/educativo/resultadoQuiz.php';
            return;

        }



        // GET: generar orden aleatorio y guardarlo en sesión
        $preguntas = require dirname(__FILE__) . '/data_quiz.php';
        shuffle($preguntas);
        $preguntas = array_slice($preguntas, 0, 10);
        // Guardar este orden exacto para que el POST lo use al calificar
        $_SESSION['quiz_preguntas'] = $preguntas;
        include_once dirname(__FILE__) . '/../../view/educativo/quiz.php';
    }


}

?>