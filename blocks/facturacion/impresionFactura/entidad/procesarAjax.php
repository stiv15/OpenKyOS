<?php
namespace facturacion\impresionFactura\entidad;

class procesarAjax
{
    public $miConfigurador;
    public $sql;
    public function __construct($sql)
    {
        $this->miConfigurador = \Configurador::singleton();

        $this->ruta = $this->miConfigurador->getVariableConfiguracion("rutaBloque");

        $this->sql = $sql;
        $conexion = "interoperacion";
        $esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);

        switch ($_REQUEST['funcion']) {

            case 'consultaBeneficiarios':

                $cadenaSql = $this->sql->getCadenaSql('consultarBeneficiariosPotenciales');

                $resultadoItems = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                foreach ($resultadoItems as $key => $values) {
                    $keys = array(
                        'value',
                        'data',
                    );
                    $resultado[$key] = array_intersect_key($resultadoItems[$key], array_flip($keys));
                }
                echo '{"suggestions":' . json_encode($resultado) . '}';

                break;

            case 'consultarProcesos':

                $cadenaSql = $this->sql->getCadenaSql('consultarProceso');
                $procesos = $esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");

                if ($procesos) {
                    foreach ($procesos as $key => $valor) {

                        $archivo = (is_null($valor['nombre_archivo'])) ? " " : "<center><a href='" . $valor['ruta_relativa_archivo'] . "' target='_blank' >" . $valor['nombre_archivo'] . "</a></center>";

                        {

                            $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

                            $url = $this->miConfigurador->getVariableConfiguracion("host");
                            $url .= $this->miConfigurador->getVariableConfiguracion("site");
                            $url .= "/index.php?";

                            $valorCodificado = "pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                            $valorCodificado .= "&action=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                            $valorCodificado .= "&bloque=" . $esteBloque['nombre'];
                            $valorCodificado .= "&bloqueGrupo=" . $esteBloque["grupo"];
                            $valorCodificado .= "&opcion=eliminarProceso";
                            $valorCodificado .= "&id_proceso=" . $valor['id_proceso'];

                        }

                        $enlace = $this->miConfigurador->getVariableConfiguracion("enlace");
                        $cadena = $this->miConfigurador->fabricaConexiones->crypto->codificar_url($valorCodificado, $enlace);

                        $urlEliminarProceso = $url . $cadena;

                        $url_eliminar = ($valor['estado'] == 'No Iniciado' || $valor['estado'] == 'Finalizado') ? '<a href="' . $urlEliminarProceso . '">Eliminar Proceso</a>' : " ";

                        $resultadoFinal[] = array(
                            'proceso' => "<center>" . $valor['id_proceso'] . "</center>",
                            'estado' => "<center>" . $valor['estado'] . "</center>",
                            'archivo' => "<center>" . $archivo . "</center>",
                            'num_inicial' => "<center>" . $valor['parametro_inicio'] . "</center>",
                            'num_final' => "<center>" . $valor['parametro_fin'] . "</center>",
                            'urbanizaciones' => "<center>" . $valor['urbanizaciones'] . "</center>",
                            'fecha_generacion' => "<center>" . substr($valor['fecha_registro'], 0, 19) . "</center>",
                            'eliminar_proceso' => "<center>" . $url_eliminar . "</center>",
                            'tamanio_archivo' => "<center>" . $valor['peso_archivo'] . "</center>",
                        );
                    }

                    $total = count($resultadoFinal);

                    $resultado = json_encode($resultadoFinal);

                    $resultado = '{
                                "recordsTotal":'     . $total . ',
                                "recordsFiltered":'     . $total . ',
                                "data":'     . $resultado . '}';
                } else {

                    $resultado = '{
                                "recordsTotal":0 ,
                                "recordsFiltered":0 ,
                                "data": 0 }'    ;
                }
                echo $resultado;

                break;

        }
    }
}

$miProcesarAjax = new procesarAjax($this->sql);
