<?php
namespace facturacion\impresionFactura\frontera;

if (!isset($GLOBALS["autorizado"])) {
    include "../index.php";
    exit();
}

/**
 * IMPORTANTE: Este formulario está utilizando jquery.
 * Por tanto en el archivo ready.php se declaran algunas funciones js
 * que lo complementan.
 */
class Registrador
{
    public $miConfigurador;
    public $lenguaje;
    public $miFormulario;
    public function __construct($lenguaje, $formulario, $sql)
    {
        $this->miConfigurador = \Configurador::singleton();

        $this->miConfigurador->fabricaConexiones->setRecursoDB('principal');

        $this->lenguaje = $lenguaje;

        $this->miFormulario = $formulario;

        $this->miSql = $sql;

        $conexion = "interoperacion";
        //$conexion = "produccion";
        $this->esteRecursoDB = $this->miConfigurador->fabricaConexiones->getRecursoDB($conexion);
    }
    public function seleccionarForm()
    {

        // Rescatar los datos de este bloque
        $esteBloque = $this->miConfigurador->getVariableConfiguracion("esteBloque");

        // ---------------- SECCION: Parámetros Globales del Formulario ----------------------------------

        $atributosGlobales['campoSeguro'] = 'true';

        $_REQUEST['tiempo'] = time();
        // -------------------------------------------------------------------------------------------------

        // ---------------- SECCION: Parámetros Generales del Formulario ----------------------------------
        $esteCampo = $esteBloque['nombre'];
        $atributos['id'] = $esteCampo;
        $atributos['nombre'] = $esteCampo;
        // Si no se coloca, entonces toma el valor predeterminado 'application/x-www-form-urlencoded'
        $atributos['tipoFormulario'] = '';
        // Si no se coloca, entonces toma el valor predeterminado 'POST'
        $atributos['metodo'] = 'POST';
        // Si no se coloca, entonces toma el valor predeterminado 'index.php' (Recomendado)
        $atributos['action'] = 'index.php';
        $atributos['titulo'] = $this->lenguaje->getCadena($esteCampo);
        // Si no se coloca, entonces toma el valor predeterminado.
        $atributos['estilo'] = '';
        $atributos['marco'] = true;
        $tab = 1;
        // ---------------- FIN SECCION: de Parámetros Generales del Formulario ----------------------------

        // ----------------INICIAR EL FORMULARIO ------------------------------------------------------------
        $atributos['tipoEtiqueta'] = 'inicio';
        echo $this->miFormulario->formulario($atributos);
        {

            {

                $esteCampo = 'AgrupacionBeneficiario';
                $atributos['id'] = $esteCampo;
                $atributos['leyenda'] = "Impresión Factura Beneficiario";
                echo $this->miFormulario->agrupacion('inicio', $atributos);
                unset($atributos);

                {

                    $esteCampo = 'seleccion_proceso';
                    $atributos['nombre'] = $esteCampo;
                    $atributos['id'] = $esteCampo;
                    $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                    $atributos["etiquetaObligatorio"] = true;
                    $atributos['tab'] = $tab++;
                    $atributos['anchoEtiqueta'] = 1;
                    $atributos['evento'] = '';
                    if (isset($_REQUEST[$esteCampo])) {
                        $atributos['seleccion'] = $_REQUEST[$esteCampo];
                    } else {
                        $atributos['seleccion'] = '1';
                    }
                    $atributos['deshabilitado'] = false;
                    $atributos['columnas'] = 1;
                    $atributos['tamanno'] = 1;
                    $atributos['ajax_function'] = "";
                    $atributos['ajax_control'] = $esteCampo;
                    $atributos['estilo'] = "bootstrap";
                    $atributos['limitar'] = false;
                    $atributos['anchoCaja'] = 3;
                    $atributos['miEvento'] = '';
                    $atributos['validar'] = 'required';
                    $atributos['cadena_sql'] = 'required';
                    $matrizItems = array(
                        array(
                            '1',
                            'Generar Consulta Facturación',
                        ),

                        array(
                            '2',
                            'Consultar Estado Generación Facturas',
                        ),
                    );
                    $atributos['matrizItems'] = $matrizItems;
                    // Aplica atributos globales al control
                    $atributos = array_merge($atributos, $atributosGlobales);
                    echo $this->miFormulario->campoCuadroListaBootstrap($atributos);
                    unset($atributos);

                    // ------------------Division para los botones-------------------------
                    $atributos["id"] = "generar_facturacion";
                    $atributos["estilo"] = "marcoBotones";
                    $atributos["estiloEnLinea"] = "display:block;";
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {

                        $esteCampo = 'departamento';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos["etiquetaObligatorio"] = true;
                        $atributos['tab'] = $tab++;
                        $atributos['anchoEtiqueta'] = 2;
                        $atributos['evento'] = '';
                        if (isset($_REQUEST[$esteCampo])) {
                            $atributos['seleccion'] = $_REQUEST[$esteCampo];
                        } else {
                            $atributos['seleccion'] = -1;
                        }
                        $atributos['deshabilitado'] = false;
                        $atributos['columnas'] = 1;
                        $atributos['tamanno'] = 1;
                        $atributos['ajax_function'] = "";
                        $atributos['ajax_control'] = $esteCampo;
                        $atributos['estilo'] = "bootstrap";
                        $atributos['limitar'] = false;
                        $atributos['anchoCaja'] = 10;
                        $atributos['miEvento'] = '';
                        //$atributos['validar'] = '';
                        $cadenaSql = $this->miSql->getCadenaSql('consultarDepartamento');
                        $resultado = $this->esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                        $atributos['matrizItems'] = $resultado;

                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroListaBootstrap($atributos);
                        unset($atributos);

                        $esteCampo = 'municipio';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos["etiquetaObligatorio"] = true;
                        $atributos['tab'] = $tab++;
                        $atributos['anchoEtiqueta'] = 2;
                        $atributos['evento'] = '';

                        if (isset($_REQUEST[$esteCampo])) {
                            $atributos['seleccion'] = $_REQUEST[$esteCampo];
                        } else {
                            $atributos['seleccion'] = '-1';
                        }
                        $atributos['deshabilitado'] = false;
                        $atributos['columnas'] = 1;
                        $atributos['tamanno'] = 1;
                        $atributos['ajax_function'] = "";
                        $atributos['ajax_control'] = $esteCampo;
                        $atributos['estilo'] = "bootstrap";
                        $atributos['limitar'] = false;
                        $atributos['anchoCaja'] = 10;
                        $atributos['miEvento'] = '';
                        //$atributos['validar'] = '';
                        $atributos['cadena_sql'] = ' ';
                        $cadenaSql = $this->miSql->getCadenaSql('consultarMunicipio');
                        $resultado = $this->esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                        $matrizItems = $resultado;
                        $atributos['matrizItems'] = $matrizItems;
                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroListaBootstrap($atributos);
                        unset($atributos);

                        $esteCampo = 'urbanizacion';
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos["etiquetaObligatorio"] = true;
                        $atributos['tab'] = $tab++;
                        $atributos['anchoEtiqueta'] = 2;
                        $atributos['evento'] = '';

                        if (isset($_REQUEST[$esteCampo])) {
                            $atributos['seleccion'] = $_REQUEST[$esteCampo];
                        } else {
                            $atributos['seleccion'] = '-1';
                        }
                        $atributos['deshabilitado'] = false;
                        $atributos['columnas'] = 1;
                        $atributos['tamanno'] = 1;
                        $atributos['ajax_function'] = "";
                        $atributos['ajax_control'] = $esteCampo;
                        $atributos['estilo'] = "bootstrap";
                        $atributos['limitar'] = false;
                        $atributos['anchoCaja'] = 10;
                        $atributos['miEvento'] = '';
                        //$atributos['validar'] = '';
                        $atributos['cadena_sql'] = ' ';
                        $cadenaSql = $this->miSql->getCadenaSql('consultarUrbanizacion');
                        $resultado = $this->esteRecursoDB->ejecutarAcceso($cadenaSql, "busqueda");
                        $matrizItems = $resultado;
                        $atributos['matrizItems'] = $matrizItems;
                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoCuadroListaBootstrap($atributos);
                        unset($atributos);

                        $esteCampo = "beneficiario";
                        $atributos['nombre'] = $esteCampo;
                        $atributos['id'] = $esteCampo;
                        $atributos['etiqueta'] = $this->lenguaje->getCadena($esteCampo);
                        $atributos["etiquetaObligatorio"] = true;
                        $atributos['tab'] = $tab++;
                        if (isset($_REQUEST[$esteCampo])) {
                            $atributos['valor'] = $_REQUEST[$esteCampo];
                        } else {
                            $atributos['valor'] = "";
                        }
                        //$atributos['validar'] = '';
                        $atributos['filas'] = 3;
                        // Aplica atributos globales al control
                        $atributos = array_merge($atributos, $atributosGlobales);
                        echo $this->miFormulario->campoTextAreaBootstrap($atributos);
                        unset($atributos);

                        // ------------------Division para los botones-------------------------
                        $atributos["id"] = "botones";
                        $atributos["estilo"] = "marcoBotones";
                        $atributos["estiloEnLinea"] = "display:block;";
                        echo $this->miFormulario->division("inicio", $atributos);
                        unset($atributos);
                        {
                            // -----------------CONTROL: Botón ----------------------------------------------------------------
                            $esteCampo = 'generar';
                            $atributos["id"] = $esteCampo;
                            $atributos["tabIndex"] = $tab;
                            $atributos["tipo"] = 'boton';
                            // submit: no se coloca si se desea un tipo button genérico
                            $atributos['submit'] = true;
                            $atributos["simple"] = true;
                            $atributos["estiloMarco"] = '';
                            $atributos["estiloBoton"] = 'default';
                            $atributos["block"] = false;
                            // verificar: true para verificar el formulario antes de pasarlo al servidor.
                            $atributos["verificar"] = '';
                            $atributos["tipoSubmit"] = 'jquery'; // Dejar vacio para un submit normal, en este caso se ejecuta la función submit declarada en ready.js
                            $atributos["valor"] = $this->lenguaje->getCadena($esteCampo);
                            $atributos['nombreFormulario'] = $esteBloque['nombre'];
                            $tab++;

                            // Aplica atributos globales al control
                            $atributos = array_merge($atributos, $atributosGlobales);
                            echo $this->miFormulario->campoBotonBootstrapHtml($atributos);
                            unset($atributos);
                            // -----------------FIN CONTROL: Botón -----------------------------------------------------------
                        }
                        // ------------------Fin Division para los botones-------------------------
                        echo $this->miFormulario->division("fin");
                        unset($atributos);

                    }
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                    // ------------------Division para los botones-------------------------
                    $atributos["id"] = "consulta";
                    $atributos["estilo"] = "marcoBotones";
                    $atributos["estiloEnLinea"] = "display:none;";
                    echo $this->miFormulario->division("inicio", $atributos);
                    unset($atributos);
                    {

                        {
                            // ------------------Division para los botones-------------------------
                            $atributos['id'] = 'divMensaje';
                            $atributos['estilo'] = 'marcoBotones';
                            echo $this->miFormulario->division("inicio", $atributos);
                            unset($atributos);
                            {
                                // -------------Control texto-----------------------
                                $esteCampo = 'mostrarMensaje';
                                $atributos["tamanno"] = '';
                                $atributos["etiqueta"] = '';
                                $mensaje = 'Consulta de Estado Generación Masiva Procesos';
                                $atributos["mensaje"] = $mensaje;
                                $atributos["estilo"] = 'information'; // information,warning,error,validation
                                $atributos["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
                                echo $this->miFormulario->campoMensaje($atributos);
                                unset($atributos);

                            }
                            // ------------------Fin Division para los botones-------------------------
                            echo $this->miFormulario->division("fin");
                            unset($atributos);

                            echo '<table id="example" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><center>Proceso<center></th>
                                            <th><center>Estado<center></th>
                                            <th><center>Archivo Descarga<center></th>
                                            <th><center>Tamaño Archivo<center></th>
                                            <th><center>Parametro Inicio<br>Id Beneficiario<center></th>
                                            <th><center>Parametro Final<br>Id Beneficiario<center></th>
                                            <th><center>Urbanizaciones<center></th>
                                            <th><center>Fecha de Generacion<center></th>
                                            <th><center>Finalizar Proceso<center></th>
                                        </tr>
                                    </thead>
                                           <tfoot>
                                        <tr>
                                            <th><center>Proceso<center></th>
                                            <th><center>Estado<center></th>
                                            <th><center>Archivo Descarga<center></th>
                                            <th><center>Tamaño Archivo<center></th>
                                            <th><center>Parametro Inicio<br>Id Beneficiario<center></th>
                                            <th><center>Parametro Final<br>Id Beneficiario<center></th>
                                            <th><center>Urbanizaciones<center></th>
                                            <th><center>Fecha de Generacion<center></th>
                                            <th><center>Finalizar Proceso<center></th>
                                        </tr>
                                    </tfoot>
                                  </table>';

                        }

                    }
                    echo $this->miFormulario->division("fin");
                    unset($atributos);

                }

                echo $this->miFormulario->agrupacion('fin');
                unset($atributos);

            }

            {
                /**
                 * En algunas ocasiones es útil pasar variables entre las diferentes páginas.
                 * SARA permite realizar esto a través de tres
                 * mecanismos:
                 * (a). Registrando las variables como variables de sesión. Estarán disponibles durante toda la sesión de usuario. Requiere acceso a
                 * la base de datos.
                 * (b). Incluirlas de manera codificada como campos de los formularios. Para ello se utiliza un campo especial denominado
                 * formsara, cuyo valor será una cadena codificada que contiene las variables.
                 * (c) a través de campos ocultos en los formularios. (deprecated)
                 */

                // En este formulario se utiliza el mecanismo (b) para pasar las siguientes variables:

                // Paso 1: crear el listado de variables
                $valorCodificado = "action=" . $esteBloque["nombre"];
                $valorCodificado .= "&pagina=" . $this->miConfigurador->getVariableConfiguracion('pagina');
                $valorCodificado .= "&bloque=" . $esteBloque['nombre'];
                $valorCodificado .= "&bloqueGrupo=" . $esteBloque["grupo"];
                $valorCodificado .= "&opcion=cargarProceso";

                /**
                 * SARA permite que los nombres de los campos sean dinámicos.
                 * Para ello utiliza la hora en que es creado el formulario para
                 * codificar el nombre de cada campo.
                 */
                $valorCodificado .= "&campoSeguro=" . $_REQUEST['tiempo'];
                // Paso 2: codificar la cadena resultante
                $valorCodificado = $this->miConfigurador->fabricaConexiones->crypto->codificar($valorCodificado);

                $atributos["id"] = "formSaraData"; // No cambiar este nombre
                $atributos["tipo"] = "hidden";
                $atributos['estilo'] = '';
                $atributos["obligatorio"] = false;
                $atributos['marco'] = true;
                $atributos["etiqueta"] = "";
                $atributos["valor"] = $valorCodificado;
                echo $this->miFormulario->campoCuadroTexto($atributos);
                unset($atributos);

            }
        }

        // ----------------FINALIZAR EL FORMULARIO ----------------------------------------------------------
        // Se debe declarar el mismo atributo de marco con que se inició el formulario.
        $atributos['marco'] = true;
        $atributos['tipoEtiqueta'] = 'fin';
        echo $this->miFormulario->formulario($atributos);
    }
    public function mensaje()
    {

        if (isset($_REQUEST['mensaje'])) {
            switch ($_REQUEST['mensaje']) {

                case 'errorBeneficiario':
                    $estilo_mensaje = 'error';     //information,warning,error,validation
                    $atributos["mensaje"] = 'Error no existe Beneficiario';
                    break;

                default:
                    # code...
                    break;
            }
            // ------------------Division para los botones-------------------------
            $atributos['id'] = 'divMensaje';
            $atributos['estilo'] = ' ';
            // echo $this->miFormulario->division("inicio", $atributos);

            // -------------Control texto-----------------------
            $esteCampo = 'mostrarMensaje';
            $atributos["tamanno"] = '';
            $atributos["estilo"] = $estilo_mensaje;
            $atributos["estiloEnLinea"] = "text-align: center;";
            $atributos["etiqueta"] = '';
            $atributos["columnas"] = ''; // El control ocupa 47% del tamaño del formulario
            echo $this->miFormulario->campoMensaje($atributos);
            unset($atributos);

            // ------------------Fin Division para los botones-------------------------
            echo $this->miFormulario->division("fin");
            unset($atributos);
        }
    }
}

$miSeleccionador = new Registrador($this->lenguaje, $this->miFormulario, $this->sql);

$miSeleccionador->mensaje();

$miSeleccionador->seleccionarForm();
