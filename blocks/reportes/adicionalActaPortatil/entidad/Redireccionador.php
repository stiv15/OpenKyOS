<?php
namespace reportes\adicionalActaPortatil\entidad;

if (!isset($GLOBALS["autorizado"])) {
    include "index.php";
    exit();
}
class Redireccionador
{
    public static function redireccionar($opcion, $valor = "")
    {

        $miConfigurador = \Configurador::singleton();

        switch ($opcion) {

            case "RegistrosProcesados":
                $variable = 'pagina=adicionalActaPortatil';
                $variable .= '&mensaje=registrosProcesados';
                $variable .= '&cantidad_registros=' . $valor;
                break;

            case 'SinResultados':
                $variable = 'pagina=adicionalActaPortatil';
                $variable .= '&mensaje=sinResultadosDocumentos';
                break;

            case 'SinRegistrosProcesados':
                $variable = 'pagina=adicionalActaPortatil';
                $variable .= '&mensaje=sinRegistrosProcesados';
                break;

        }

        foreach ($_REQUEST as $clave => $valor) {
            unset($_REQUEST[$clave]);
        }

        $url = $miConfigurador->configuracion["host"] . $miConfigurador->configuracion["site"] . "/index.php?";
        $enlace = $miConfigurador->configuracion['enlace'];
        $variable = $miConfigurador->fabricaConexiones->crypto->codificar($variable);
        $_REQUEST[$enlace] = $enlace . '=' . $variable;
        $redireccion = $url . $_REQUEST[$enlace];

        echo "<script>location.replace('" . $redireccion . "')</script>";

        exit();
    }
}
