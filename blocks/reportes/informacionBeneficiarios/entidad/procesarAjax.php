<?php
namespace reportes\informacionBeneficiarios\entidad;
class procesarAjax {
    public $miConfigurador;
    public $sql;
    public function __construct($sql) {
        $this->miConfigurador = \Configurador::singleton();

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

        $this->sql = $sql;

        switch ($_REQUEST['funcion']) {

            case 'consultarProyectos':
                include_once "consultarProyectos.php";
                break;

        }
    }
}

$miProcesarAjax = new procesarAjax($this->sql);

?>