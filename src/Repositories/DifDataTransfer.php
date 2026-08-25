<?php declare(strict_types=1);

namespace JuanchoSL\DataTransfer\Repositories;

use JuanchoSL\DataManipulation\Manipulators\Arrays\ArrayManipulators;

class DifDataTransfer extends ArrayDataTransfer
{
    public function __construct(array|string $dif)
    {
        if (is_string($dif)) {
            if (is_file($dif) && file_exists($dif)) {
                $dif = file($dif, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            } else {
                $dif = str_replace(["\r\n", "\r"], "\n", $dif);
                $dif = explode("\n", $dif);
            }
        }
        if (is_string($dif[0]) && $dif[0] == 'TABLE') {

            $datos_tabla = [];
            $fila_actual = [];
            $procesando_datos = false;
            $jump = 0;
            foreach ($dif as $i => $linea) {
                // Detecta el inicio de la sección de datos
                if ($linea === 'DATA') {
                    $procesando_datos = true;
                    $datos_tabla = [];
                    $jump += 2;
                    continue;
                }
                if ($procesando_datos) {
                    // En la sección DATA, vienen pares: indicador (ej. "1,0") y el valor (ej. "Hola")
                    // Si la línea empieza con -1 (fin de vector/fila), cambiamos de fila
                    if ($jump > 0) {
                        $jump--;
                        continue;
                    }
                    if (strpos($linea, '-1,') === 0) {
                        if (!empty($fila_actual)) {
                            $datos_tabla[] = $fila_actual;
                            $fila_actual = [];
                        }
                        continue;
                    }
                    // Si la línea anterior fue un indicador de datos, esta línea es el valor
                    // (Esta es una simplificación; un parser robusto valida la estructura numérica previa)
                    if ($linea == 'BOT') {
                        continue;
                    } elseif ($linea == '1,0') {
                        $linea = trim($dif[$i + 1], '"');
                        $jump++;
                    } elseif (substr($linea, 0, 2) == '0,') {
                        $type = $dif[$i + 1];
                        if ($type == 'V') {
                            $linea = substr($linea, 2);
                        } elseif ($type == 'TRUE') {
                            $linea = true;
                        } elseif ($type == 'FALSE') {
                            $linea = false;
                        }
                        $jump++;
                    }
                    $fila_actual[] = $linea;
                }
            }
            $arr = (new ArrayManipulators)->combine($datos_tabla[0]);
            $datos_tabla = $arr(...array_slice($datos_tabla, 1));
        } else {
            $datos_tabla = $dif;
        }
        parent::__construct($datos_tabla);
    }
}