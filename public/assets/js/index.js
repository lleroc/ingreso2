function init() {
  $("#formAuthentication").on("submit", (e) => {
    RegistroAsistencia(e);
  });
}
$().ready(() => {
  tiposacceso();
});
var RegistroAsistencia = (e) => {
  e.preventDefault();
  var formulario = new FormData($("#formAuthentication")[0]);
  $.ajax({
    url: "",
    type: "post",
    data: formulario,
    cache: false,
    success: (respuesta) => {
      respuesta = JSON.parse(respuesta);
      if (respuesta == "ok") {
        //Swal.fire(Titulo, texto, tipo de alerta)
        Swal.fire("Registro de Asistencia", "Se guardo con éxito", "success");
      } else {
        Swal.fire(
          "Registro de Asistencia",
          "Hubo un error al guardar",
          "danger"
        );
      }
    },
  });
};

var tiposacceso = () => {
  return new Promise((resolve, reject) => {
    var html = `<option value="0">Seleccione una opción</option>`;
    $.post("controllers/tipoacceso.controllers.php?op=todos", async (lista) => {
      lista = JSON.parse(lista);
      $.each(lista, (index, tipo) => {
        html += `<option value="${tipo.IdTipoAcceso}">${tipo.Detalle}</option>`;
      });
      await $("#tipo").html(html);
      resolve();
    }).fail((error) => {
      reject(error);
    });
  });
};
