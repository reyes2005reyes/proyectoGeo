<div class="container mt-4">


    <div class="row mb-4">

        <div class="col-md-12 text-center">


            <h2 class="text-primary">
                Resultado Quiz Educación Vial
            </h2>


            <p class="lead">
                Aquí puedes ver tu desempeño en el cuestionario.
            </p>


            <hr>


        </div>

    </div>



    <div class="row">


        <div class="col-md-8 offset-md-2">


            <div class="card shadow-sm text-center">


                <div class="card-body">



                    <span class="badge badge-primary">
                        Resultado
                    </span>



                    <h1 class="mt-4">


                        <?php echo $calificacion['puntos']; ?>

                        /

                        <?php echo $calificacion['total']; ?>


                    </h1>



                    <h4 class="mt-3">

                        <?php echo round($calificacion['porcentaje']); ?> %

                    </h4>




                    <?php if($calificacion['porcentaje'] >= 80){ ?>


                        <div class="alert alert-success mt-4">

                            Excelente trabajo.
                            Tienes buen conocimiento de las señales de tránsito.

                        </div>



                    <?php }else{ ?>



                        <div class="alert alert-warning mt-4">

                            Puedes seguir practicando las señales de tránsito.

                        </div>



                    <?php } ?>
                    
                    <a href="<?php echo getUrl('educativo','educativo','quiz',false); ?>">
                        Intentar nuevamente
                    </a>

                </div>


            </div>


        </div>


    </div>


</div>