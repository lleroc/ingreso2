function init() {
  $("#form_usuarios").on("submit", (e) => {
    GuardarEditar(e);
  });
}
var ruta = "../../controllers/usuario.controllers.php?op=";
$().ready(() => {
  CargaLista();
});

var CargaLista = () => {
  var html = "";
  $.get(ruta + "todos", (ListUsuarios) => {
    ListUsuarios = JSON.parse(ListUsuarios);
    $.each(ListUsuarios, (index, usuario) => {
      html += `<tr>
            <td>${index + 1}</td>
            <td> <img src='${usuario.imagen}' width='50px' height='50px'/></td> 
            <td>${usuario.Nombres}</td>
            <td>${usuario.Apellidos}</td>
            <td>${usuario.Rol}</td>
            <td>${usuario.Nombre}</td>
<td>
<button class='btn btn-primary' onclick='uno(${
        usuario.idUsuarios
      })'   data-bs-toggle="modal" data-bs-target="#ModalUsuarios">Editar</button>
<button class='btn btn-danger' onclick='eliminar(${
        usuario.idUsuarios
      })'>Eliminar</button>
            `;
    });
    $("#ListaUsuarios").html(html);
  });
};

var GuardarEditar = (e) => {
  e.preventDefault();
  var DatosFormularioUsuario = new FormData($("#form_usuarios")[0]);
  var accion = "";

  if (document.getElementById("idUsuarios").value != "") {
    accion = ruta + "actualizar";
  } else {
    accion = ruta + "insertar";
  }
  $.ajax({
    url: accion,
    type: "post",
    data: DatosFormularioUsuario,
    processData: false,
    contentType: false,
    cache: false,
    success: (respuesta) => {
      console.log(respuesta);
      respuesta = JSON.parse(respuesta);
      if (respuesta == "ok") {
        Swal.fire({
          title: "Usuarios!",
          text: "Se guardó con éxito",
          icon: "success",
        });
        CargaLista();
        LimpiarCajas();
      } else {
        Swal.fire({
          title: "Usuarios!",
          text: "Error al guradar",
          icon: "error",
        });
      }
    },
  });
};

var uno = async (idUsuarios) => {
  await sucursales();
  await roles();
  document.getElementById("tituloModal").innerHTML = "Actualizar Usuarios";
  $.post(ruta + "uno", { idUsuarios: idUsuarios }, (usuario) => {
    usuario = JSON.parse(usuario);
    document.getElementById("idUsuarios").value = usuario.idUsuarios;
    document.getElementById("Cedula").value = usuario.Cedula;
    document.getElementById("Nombres").value = usuario.Nombres;
    document.getElementById("Apellidos").value = usuario.Apellidos;
    document.getElementById("Correo").value = usuario.Correo;
    document.getElementById("contrasenia").value = usuario.contrasenia;
    document.getElementById("RolId").value = usuario.idRoles;
    document.getElementById("SucursalId").value = usuario.SucursalId;
  });
};
var unoconCedula = () => {
  var cedula = document.getElementById("Cedula").value;
  $.post(ruta + "unoconCedula", { cedula: cedula }, (usuario) => {
    usuario = JSON.parse(usuario);
    if (parseInt(usuario.numero) > 0) {
      Swal.fire({
        title: "Usuarios!",
        text: "Ya existe un usuario con esa cedula",
        icon: "error",
      });
      document.getElementById("Cedula").value = "";
    }
  });
};
var unoconCorreo = () => {
  var Correo = document.getElementById("Correo").value;
  $.post(ruta + "unoconCorreo", { Correo: Correo }, (usuario) => {
    usuario = JSON.parse(usuario);
    if (parseInt(usuario.numero) > 0) {
      Swal.fire({
        title: "Usuarios!",
        text: "Ya existe un usuario con ese correo",
        icon: "error",
      });
      document.getElementById("Correo").value = "";
    }
  });
};
var sucursales = () => {
  return new Promise((resolve, reject) => {
    var html = `<option value="0">Seleccione una opción</option>`;
    $.post(
      "../../controllers/sucursal.controllers.php?op=todos",
      async (ListaSucursales) => {
        ListaSucursales = JSON.parse(ListaSucursales);
        $.each(ListaSucursales, (index, sucursal) => {
          html += `<option value="${sucursal.SucursalId}">${sucursal.Nombre}</option>`;
        });
        await $("#SucursalId").html(html);
        resolve();
      }
    ).fail((error) => {
      reject(error);
    });
  });
};

var roles = () => {
  return new Promise((resolve, reject) => {
    var html = `<option value="0">Seleccione una opción</option>`;
    $.post(
      "../../controllers/rol.controllers.php?op=todos",
      async (ListaRoles) => {
        ListaRoles = JSON.parse(ListaRoles);
        $.each(ListaRoles, (index, rol) => {
          html += `<option value="${rol.idRoles}">${rol.Rol}</option>`;
        });
        await $("#RolId").html(html);
        resolve();
      }
    ).fail((error) => {
      reject(error);
    });
  });
};

var eliminar = (idUsuarios) => {
  Swal.fire({
    title: "Usuarios",
    text: "Esta segurpo que desea eliminar el usuario",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Eliminar",
  }).then((result) => {
    if (result.isConfirmed) {
      $.post(ruta + "eliminar", { idUsuarios: idUsuarios }, (respuesta) => {
        respuesta = JSON.parse(respuesta);
        if (respuesta == "ok") {
          CargaLista();
          Swal.fire({
            title: "Usuarios!",
            text: "Se emliminó con éxito",
            icon: "success",
          });
        } else {
          Swal.fire({
            title: "Usuarios!",
            text: "Error al guradar",
            icon: "error",
          });
        }
      });
    }
  });
};

var LimpiarCajas = () => {
  document.getElementById("idUsuarios").value = "";
  document.getElementById("Cedula").value = "";
  document.getElementById("Nombres").value = "";
  document.getElementById("Apellidos").value = "";
  document.getElementById("Correo").value = "";
  document.getElementById("contrasenia").value = "";
  document.getElementById("tituloModal").innerHTML = "Insertar Usuario";
  $("#ModalUsuarios").modal("hide");
};
init();
