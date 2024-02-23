<?php
//TODO: Requerimientos 
require_once('../config/conexion.php');

class Usuarios
{

    public function Insertar($Nombres, $Apellidos, $Correo, $Contrasenia, $SucursalId, $idRoles, $Cedula)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "INSERT into Usuarios(Nombres,Apellidos,Correo,Contrasenia, SucursalId, Cedula) values ( '$Nombres', '$Apellidos', '$Correo', '$Contrasenia', $SucursalId, '$Cedula')";

        if (mysqli_query($con, $cadena)) {
            $id = mysqli_insert_id($con);
            require_once('../models/Usuarios_Roles.models.php');
            $UsRoles = new Usuarios_Roles();

            $dato = $UsRoles->Insertar($id, $idRoles);
            if ($dato == 'ok') {
                $usuImagen = new Usuarios;
                return $usuImagen->InsertarImagen($id);
            } else {
                return "Error al insertar el rol";
            }
        } else {
            return 'Error al insertar en la base de datos el usuario';
        }
        $con->close();
    }
    public function InsertarImagen($idUsuarios)
    {
        if ($_FILES["imagenUsuario"]["name"] != '') {
            $extesion = explode(".", $_FILES["imagenUsuario"]["name"]);
            $nombreNuevo = $idUsuarios . '.' . end($extesion);
            $destino = "../public/images/usuarios/" . $nombreNuevo;  //para guardar la imagen en el servidor    ../
            copy($_FILES["imagenUsuario"]["tmp_name"], $destino);
            $con = new ClaseConectar();
            $con = $con->ProcedimientoConectar();
            //para guardar en la base de datos ../../
            $destino = '../' . $destino; //para guardar en la base de datos
            $cadena = "UPDATE Usuarios SET imagen = '$destino' WHERE idUsuarios = $idUsuarios";
            if (mysqli_query($con, $cadena)) {
                return 'ok';
            } else {
                return 'Error al guardar la imagen';
            }
        }
    }


    public function todos()
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "SELECT Usuarios.idUsuarios, Usuarios.Nombres, Usuarios.Contrasenia, Usuarios.Apellidos, Usuarios.Correo, Roles.Rol, Roles.idRoles, Sucursales.Nombre, Usuarios.imagen  from Usuarios INNER JOIN Usuarios_Roles on Usuarios.idUsuarios = Usuarios_Roles.Usuarios_idUsuarios INNER JOIN Roles ON Usuarios_Roles.Roles_idRoles = Roles.idRoles INNER JOIN Sucursales on Usuarios.SucursalId = Sucursales.SucursalId";
        $datos = mysqli_query($con, $cadena);
        return $datos;
        $con->close();
    }
    /*TODO: Procedimiento para sacar un registro*/
    public function uno($idUsuarios)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "SELECT Usuarios.*, Roles.Rol, Roles.idRoles from Usuarios INNER JOIN Usuarios_Roles on Usuarios.idUsuarios = Usuarios_Roles.Usuarios_idUsuarios INNER JOIN Roles ON Usuarios_Roles.Roles_idRoles = Roles.idRoles  WHERE Usuarios.idUsuarios = $idUsuarios";
        $datos = mysqli_query($con, $cadena);
        return $datos;
        $con->close();
    }
    public function unoconCedula($Cedula)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "SELECT count(*) as numero FROM `Usuarios` WHERE `Cedula`='$Cedula'";
        $datos = mysqli_query($con, $cadena);
        return $datos;
        $con->close();
    }
    public function unoconCorreo($Correo)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "SELECT count(*) as numero FROM `Usuarios` WHERE `Correo`='$Correo'";
        $datos = mysqli_query($con, $cadena);
        return $datos;
        $con->close();
    }
    /*TODO: Procedimiento para actualizar */
    public function Actualizar($idUsuarios, $Nombres, $Apellidos, $Correo, $Contrasenia, $idRoles, $Cedula)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "update Usuarios set Nombres='$Nombres',Apellidos='$Apellidos',Correo='$Correo',Contrasenia='$Contrasenia',Roles_idRoles=$idRoles, Cedula='$Cedula' where idUsuarios= $idUsuarios";
        if (mysqli_query($con, $cadena)) {
            $id = mysqli_insert_id($con);
            $usuImagen = new Usuarios;
            return $usuImagen->InsertarImagen($id);
        } else {
            return 'error al actualizar el registro';
        }
        $con->close();
    }
    /*TODO: Procedimiento para Eliminar */
    public function Eliminar($idUsuarios)
    {
        require_once('Usuarios_Roles.models.php');
        $UsRoles = new Usuarios_Roles();
        $usro = $UsRoles->Eliminar($idUsuarios);
        if ($usro == 'ok') {
            $con = new ClaseConectar();
            $con = $con->ProcedimientoConectar();
            $cadena = "delete from Usuarios where idUsuarios = $idUsuarios";

            return "ok";
        } else {
            return false;
        }
        $con->close();
    }

    public function login($Correo)
    {
        try {
            $con = new ClaseConectar();
            $con = $con->ProcedimientoConectar();
            $cadena = "SELECT Usuarios.idUsuarios, Usuarios.Nombres, Usuarios.Contrasenia, Usuarios.Apellidos, Usuarios.Correo, Roles.Rol, Roles.idRoles, Usuarios.SucursalId from Usuarios INNER JOIN Usuarios_Roles on Usuarios.idUsuarios = Usuarios_Roles.Usuarios_idUsuarios INNER JOIN Roles ON Usuarios_Roles.Roles_idRoles = Roles.idRoles WHERE `Correo`='$Correo'";
            $datos = mysqli_query($con, $cadena);
            return $datos;
        } catch (Throwable $th) {
            return $th->getMessage();
        }
        $con->close();
    }
}


   //WhatsApp Image 2024-02-22 at 09.25.05.jpeg
            // [0]  => WhatsApp Image 2024-02-22 at 09
            // [1]  => 25
            // [2] => 05
            // [3] => jpeg
            //$nombreNuevo = $idUsuarios .'-'.$extesion[3];
            //mifoto.png
            //$nombreNuevo = $idUsuarios .'-'.$extesion[1];
            //$constador=0;
            //for ($i = 0; $i < count($extesion); $i++){ 
            //    $constador++;
            //}
            //$nombreNuevo = $idUsuarios .'-'.$extesion[$constador-1];