$(document).ready(function() {
    $(".mt-4 div").hide(); // Asegura que las tablas estén ocultas al inicio

    $("#btnVentas").click(function() {
        $(".mt-4 div").slideUp();
        $("#tablaVentas").stop(true, true).slideDown();
    });

    $("#btnProducto").click(function() {
        $(".mt-4 div").slideUp();
        $("#tablaProducto").stop(true, true).slideDown();
    });

    $("#btnIngresos").click(function() {
        $(".mt-4 div").slideUp();
        $("#tablaIngresos").stop(true, true).slideDown();
    });
    
    $("#btnStock").click(function() {
        $(".mt-4 div").hide(); // Oculta todas las tablas
        $("#tablaStock").stop(true, true).fadeIn(); // Muestra la tabla correcta
    });

 $("#btnCSV").click(function() {
        Swal.fire({
            title: "¿Quieres descargar el reporte en CSV?",
            text: "Esto generará un archivo con los datos de ventas.",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Sí, exportar",
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "consultas/exportar_csv.php";
            }
        });
    });
});