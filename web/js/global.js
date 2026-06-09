$(documento).ready(function () {
    $(documento).on("keyup", "filtro", function () {
        
        let data = $(this).val();
        let url = $(this).data("url");

        $.ajax({
            url: url,
            type: "GET",
            data: data, // Esto se puede personalizar, para mas info ver video 4 min 17:40
            success: function(data){
                $("tbody").html(data);
            }
        })
    });
});

// Este js es importante para los listados de usuarios y solicitudes ya que ayudan a realizar filtros
// Por favor implementar en sus respectivos modulos