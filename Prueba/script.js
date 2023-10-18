$(document).ready(function() {
    //Obtiene las regiones y comunas
    $.ajax({
        type: "GET",
        url: "region_comuna.php",
        dataType: "json",
        success: function(regiones) {
            var selectRegion = $("#region");
            var selectComuna = $("#comuna");

            for (var i = 0; i < regiones.length; i++) {
                selectRegion.append("<option value='" + regiones[i].id + "'>" + regiones[i].nombre + "</option>");
            }

            selectRegion.on("change", function() {
                var regionId = $(this).val();
                selectComuna.empty();
                selectComuna.append("<option value='0'>Selecciona una comuna</option>");

                if (regionId != "0") {
                    var region = regiones.find(function(region) {
                        return region.id === regionId;
                    });

                    if (region && region.comunas) {
                        for (var i = 0; i < region.comunas.length; i++) {
                            selectComuna.append("<option>" + region.comunas[i] + "</option>");
                        }
                    }
                }
            });
        },
        error: function(xhr, status, error) {
            alert("Error: " + error);
        }
    });

    //Obtiene los candidatos
    $.ajax({
        type: "GET",
        url: "candidatos.php",
        dataType: "json",
        success: function(candidatos) {
            var selectCandidato = $("#candidato");

            for (var i = 0; i < candidatos.length; i++) {
                selectCandidato.append("<option value='" + candidatos[i].id + "'>" + candidatos[i].nombre + "</option>");
            }
        },
        error: function(xhr, status, error) {
            alert("Error: " + error);
        }
    });

    //Obtener formulario
    $("#formulario").submit(function(event) {
        event.preventDefault();
    
        var formData = $(this).serialize();
    
        //Conexión con el archivo php
        $.ajax({
            type: "POST",
            url: "form.php",
            data: formData,
            success: function(response) {
                alert(response);
            },
            error: function(xhr, status, error) {
                alert("Error: " + error);
            }
        });
    });
});

//Validar que se seleccione región y comuna
$(document).ready(function() {
    $("#formulario").submit(function(event) {
        var region = $("#region").val();
        var comuna = $("#comuna").val();
        
        if (region === "0" || comuna === "0") {
            alert("Por favor, selecciona una región y una comuna válida.");
            event.preventDefault();
        }
    });
});