<div class="container mt-4">


    <div class="row mb-4">

        <div class="col-md-12 text-center">

            <h2 class="text-primary">
                Quiz Educación Vial
            </h2>


            <p class="lead">
                Pon a prueba tus conocimientos sobre señales de tránsito,
                señales preventivas, reglamentarias e informativas.
            </p>


            <hr>

        </div>

    </div>



    <form method="POST">


        <div class="row">


        <?php foreach($preguntas as $key => $pregunta){ ?>


            <div class="col-md-6 mb-4">


                <div class="card h-100 shadow-sm">


                    <div class="card-body text-center">


                        <span class="badge badge-primary">

                            <?php echo $pregunta['codigo']; ?>

                        </span>


                        <span class="badge badge-secondary">

                            <?php echo $pregunta['categoria']; ?>

                        </span>



                        <h5 class="mt-3">

                            <?php echo ($key + 1) . ". " . $pregunta['pregunta']; ?>

                        </h5>



                        <img
                            src="<?php echo $pregunta['imagen']; ?>"
                            class="img-fluid mb-3"
                            style="max-height:120px;"
                        >



                        <div class="text-left">


                        <?php foreach($pregunta['opciones'] as $opcion){ ?>


                            <div class="form-check mb-2">


                                <input

                                class="form-check-input"

                                type="radio"

                                required

                                name="respuesta[<?php echo $key; ?>]"

                                value="<?php echo $opcion; ?>"
                                >


                                <label class="form-check-label">

                                    <?php echo $opcion; ?>

                                </label>


                            </div>


                        <?php } ?>


                        </div>


                    </div>


                </div>


            </div>


        <?php } ?>


        </div>



        <div class="row mt-3 mb-5">


            <div class="col-md-12 text-center">


                <button 
                type="submit"
                class="btn btn-primary btn-lg"
                >

                    Calificar Quiz

                </button>


            </div>


        </div>



    </form>


</div>