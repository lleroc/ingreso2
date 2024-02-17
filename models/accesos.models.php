<?php
//TODO: Requerimientos 
require_once('../config/conexion.php');
require_once('./usuario.models.php');
class Accesos
{
    /*TODO: Procedimiento para sacar todos los registros*/
    public function todos()
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "SELECT Accesos.*, Usuarios.Nombres, Usuarios.Apellidos FROM `Accesos` inner JOIN Usuarios on Accesos.Usuarios_idUsuarios = Usuarios.idUsuarios;";
        $datos = mysqli_query($con, $cadena);
        return $datos;
        $con->close();
    }
    /*TODO: Procedimiento para sacar un registro*/
    public function uno($idAccesos)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        $cadena = "SELECT  * FROM Accesos WHERE idAccesos = $idAccesos";
        $datos = mysqli_query($con, $cadena);
        return $datos;
        $con->close();
    }
    /*TODO: Procedimiento para insertar */
    public function Insertar($Cedula, $IdTipoAcceso)
    {
        $con = new ClaseConectar();
        $con = $con->ProcedimientoConectar();
        //declarar a la usuarios y llamar procedimeinto unoCedula

        $usuario = new Usuarios();
        $datosusuario = $usuario->unoconCedula($Cedula);
        $res = mysqli_fetch_assoc($datosusuario);
        $usuariosId = $res["idUsuarios"];

        $cadena = "INSERT into Accesos(Usuarios_idUsuarios,IdTipoAcceso) values ( $usuariosId, $IdTipoAcceso)";

        echo $cadena;

        if (mysqli_query($con, $cadena)) {
            return "ok";
        } else {
            return mysqli_error($con);
        }
        $con->close();
    }
}
