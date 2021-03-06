<?php

namespace gestionComisionamiento\gestionRequisitos;

if (!isset($GLOBALS["autorizado"])) {
    include "../index.php";
    exit();
}

include_once "core/manager/Configurador.class.php";
include_once "core/builder/InspectorHTML.class.php";
include_once "core/builder/Mensaje.class.php";
include_once "core/crypto/Encriptador.class.php";

// Esta clase contiene la logica de negocio del bloque y extiende a la clase funcion general la cual encapsula los
// metodos mas utilizados en la aplicacion

// Para evitar redefiniciones de clases el nombre de la clase del archivo funcion debe corresponder al nombre del bloque
// en camel case precedido por la palabra Funcion
class Entidad {
    public $sql;
    public $entidad;
    public $lenguaje;
    public $ruta;
    public $miConfigurador;
    public $error;
    public $miRecursoDB;
    public $crypto;
    public function verificarCampos() {
        include_once $this->ruta . "/funcion/verificarCampos.php";
        if ($this->error == true) {
            return false;
        } else {
            return true;
        }
    }
    public function redireccionar($opcion, $valor = "") {
        include_once $this->ruta . "entidad/Redireccionador.php";
    }
    public function procesarAjax() {
        include_once $this->ruta . "entidad/procesarAjax.php";
    }
    public function cargarRequisitos() {
        include_once $this->ruta . "entidad/cargarRequisitos.php";
    }
    public function generarContratoPdf() {
        include_once $this->ruta . "entidad/generarContratoPdf.php";
    }
    public function procesarContrato() {
        include_once $this->ruta . "entidad/guardarContrato.php";
    }
    public function modificarArchivo() {
        include_once $this->ruta . "entidad/modificarArchivo.php";
    }
    public function action() {
        $resultado = true;

        if (isset($_REQUEST['procesarAjax'])) {
            $this->procesarAjax();
        }

        switch ($_REQUEST['opcion']) {
            case 'cargarRequisitos':
                $this->cargarRequisitos();
                break;

            case 'generarContratoPDF':
                $this->generarContratoPdf();
                break;

            case 'guardarContrato':
                $this->procesarContrato();
                break;

            case 'modificarArchivo':
                $this->modificarArchivo();
                break;
        }

        return $resultado;
    }
    public function __construct() {
        $this->miConfigurador = \Configurador::singleton();

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

        $this->miMensaje = \Mensaje::singleton();

        $conexion = "aplicativo";
        $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        if (!$this->miRecursoDB) {

            $this->miConfigurador->fabricaConexiones->setRecursoDB($conexion, "tabla");
            $this->miRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
        }
    }
    public function setRuta($unaRuta) {
        $this->ruta = $unaRuta;
    }
    public function setSql($a) {
        $this->sql = $a;
    }
    public function setEntidad($entidad) {
        $this->entidad = $entidad;
    }
    public function setLenguaje($lenguaje) {
        $this->lenguaje = $lenguaje;
    }
    public function setFormulario($formulario) {
        $this->formulario = $formulario;
    }
}

?>

