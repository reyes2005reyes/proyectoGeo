$(document).ready(function () {
    $(document).on("keyup", "#filtro", function () {
        let data = $(this).val();
        let url = $(this).data("url");
        console.log(url);

        $.ajax({
            url: url,
            type: "GET",
            data: {
                buscar: data
            },
            success: function (data) {
                $("#usuariosFiltro").html(data);
            }
        })
    });
});
