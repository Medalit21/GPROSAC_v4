<?php

require_once "../../../models/get_categorias.php";
require_once("../../../models/excel/vendor/autoload.php");
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ControllerExportar
{

	public $mo;

	public function __construct()
	{
		//$this->load->model('get_categorias');		
		$this->mo = new get_categorias();
	}

    public function exportarExcel(){

		$txtFiltroDocumentoCP = $this->input->post('txtFiltroDocumentoCP');
        $bxFiltroProyectoCP = $this->input->post('bxFiltroProyectoCP');
        $bxFiltroZonaCP = $this->input->post('bxFiltroZonaCP');
        $bxFiltroManzanaCP = $this->input->post('bxFiltroManzanaCP');
        $bxFiltroLoteCP = $this->input->post('bxFiltroLoteCP');
        $bxFiltroEstadoCP = $this->input->post('bxFiltroEstadoCP');

        $idPersona = 0;
        $p_desde = 0; 
        $p_hasta = 0; 
        $p_estado = 0;

        $spreadSheet = new Spreadsheet();
    
        $name = "REPORTE DE CRONOGRAMA DE PAGOS MASIVO";
        $spreadSheet -> getProperties() -> setCreator("ACG EXTRANET") -> setTitle($name);
    
        $spreadSheet -> setActiveSheetIndex(0);
        $hojaActiva = $spreadSheet -> getActiveSheet();
        //$hojaActiva->setTitle('Colaboradores y Actividades');
    
        $spreadSheet -> getDefaultStyle() -> getfont() -> setName('Calibri');
        $spreadSheet -> getDefaultStyle() -> getfont() -> setSize(11);
        $styleArrayFirstRow = [
            'font' => [
                'bold' => true,
            ]
        ];
    
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border:: BORDER_THIN,
                    'color' => ['argb' => '00000000'], // Color del borde en formato ARGB (Alpha, Red, Green, Blue)
                ],
            ],
        ];
    
        $style = [
            'numberFormat' => [
                'formatCode' => '0.00', // Formato para dos decimales
            ],
        ];
    
        // Ruta de la imagen que deseas insertar
        $ruta_imagen = 'assets/media/logos/acg-logo.png';
    
    
        // Insertar el logo en la celda B2
        $logo = new Drawing();
        $logo -> setPath($ruta_imagen); // Ruta del logo
        $logo -> setCoordinates('A1');
        $logo -> setOffsetX(30);
        $logo -> setOffsetY(5);
        // $logo->setRotation(25);
        $logo -> getShadow() -> setVisible(true);
        $logo -> getShadow() -> setDirection(45);
        $logo -> setWorksheet($hojaActiva);
    
        // Ajustar el tamaño del logo
        $logo -> setWidthAndHeight(34, 34);
    
    
    
        $hojaActiva -> setCellValue('A1', 'REPORTE DE PAGOS DE MOVILIDAD');
        $hojaActiva -> mergeCells('A1:L1');
        $hojaActiva -> getStyle('A1:L1') -> getFont() -> setBold(true);
        $hojaActiva -> getStyle('A1:L1') -> getFont() -> setSize(14);
        $hojaActiva -> getStyle('A1') -> getAlignment() -> setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment:: HORIZONTAL_CENTER);
    
        $listaUsuario = $this -> get_categorias -> VerListaClientesGpro($txtFiltroDocumentoCP, $bxFiltroProyectoCP, $bxFiltroZonaCP, $bxFiltroManzanaCP, $bxFiltroLoteCP, $bxFiltroEstadoCP);
    
    
        $filaUser = 4;
        $filaVisita = 5;
        $numPagos = 1;
        $fila = 2;
        $i = 1;
        $j = 1;
        $importe = 0;
        $saldo = 0;
        $pagado = 0;
        $impTotal = 0;
        $saldoTotal = 0;
        $pagadoTotal = 0;
        //for ($i=0; $i < 3; $i++) { 
        foreach($listaUsuario as $row) {
            // $numCol=$numCol+$i;
            // Datos de los colaboradores
            $cantUser = count($listaUsuario);  
		    if($i==1){
                // Encabezados para los colaboradores
                $hojaActiva -> setCellValue('A3', 'FECHA VENTA');
                $hojaActiva -> setCellValue('B3', 'CLIENTE');
                $hojaActiva -> setCellValue('C3', 'LOTE');
                $hojaActiva -> setCellValue('D3', 'VENTA');
                $hojaActiva -> setCellValue('E3', 'INTERESES');
                $hojaActiva -> setCellValue('F3', 'FINANCIADO');
                $hojaActiva -> setCellValue('G3', 'PENDIENTE');
                $hojaActiva -> setCellValue('H3', 'PAGADO');
                $hojaActiva -> setCellValue('I3', 'ESTADO');

                //COMBINAR CELDAS
            /* $hojaActiva -> mergeCells('A3:E3');
                $hojaActiva -> mergeCells('F3:H3');
                $hojaActiva -> mergeCells('I3:K3');*/

                //PONER NEGRITA A LOS NOMBRES DE LA CELDAS
                $hojaActiva -> getStyle('A3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('B3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('C3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('D3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('E3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('F3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('G3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('H3') -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('I3') -> getFont() -> setBold(true);

                //COLOR DE FONDO Y COLOR DE FUENTE
                $spreadSheet -> getActiveSheet() -> getStyle('A3:I3') -> getFill()
                    -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill:: FILL_SOLID)
                    -> getStartColor() -> setARGB('D9D9D9');  //Color de fondo
                $spreadSheet -> getActiveSheet() -> getStyle('A3:I3') -> getFont() -> getColor() -> setRGB('000000'); //Color de texto

                //ASIGNAR VALORES
                $hojaActiva -> setCellValue('A'.$filaUser, $row -> fecha);
                $hojaActiva -> setCellValue('B'.$filaUser, $row -> cliente);
                $hojaActiva -> setCellValue('C'.$filaUser, $row -> lote);
                $hojaActiva -> setCellValue('D'.$filaUser, $row -> total_venta);
                $hojaActiva -> setCellValue('E'.$filaUser, $row -> intereses);
                $hojaActiva -> setCellValue('F'.$filaUser, $row -> total_financiado);
                $hojaActiva -> setCellValue('G'.$filaUser, $row -> total_pendiente);
                $hojaActiva -> setCellValue('H'.$filaUser, $row -> total_pagado);
                $hojaActiva -> setCellValue('I'.$filaUser, $row -> estado);

                //ESTABLECER MARGENES DE COLUMNAS - INICIO Y FIN
                $hojaActiva -> mergeCells('A'.$filaUser.':B'.$filaUser);
                $hojaActiva -> mergeCells('B'.$filaUser.':C'.$filaUser);
                $hojaActiva -> mergeCells('C'.$filaUser.':D'.$filaUser);
                $hojaActiva -> mergeCells('D'.$filaUser.':E'.$filaUser);
                $hojaActiva -> mergeCells('E'.$filaUser.':F'.$filaUser);
                $hojaActiva -> mergeCells('F'.$filaUser.':G'.$filaUser);
                $hojaActiva -> mergeCells('G'.$filaUser.':H'.$filaUser);
                $hojaActiva -> mergeCells('H'.$filaUser.':I'.$filaUser);
                $hojaActiva -> mergeCells('I'.$filaUser.':J'.$filaUser);

                $Codigo = $row -> id;
            
            }else{

                // Encabezados para los colaboradores
                $hojaActiva -> setCellValue('A'.$filaUser, 'FECHA VENTA');
                $hojaActiva -> setCellValue('B'.$filaUser, 'CLIENTE');
                $hojaActiva -> setCellValue('C'.$filaUser, 'LOTE');
                $hojaActiva -> setCellValue('D'.$filaUser, 'VENTA');
                $hojaActiva -> setCellValue('E'.$filaUser, 'INTERESES');
                $hojaActiva -> setCellValue('F'.$filaUser, 'FINANCIADO');
                $hojaActiva -> setCellValue('G'.$filaUser, 'PENDIENTE');
                $hojaActiva -> setCellValue('H'.$filaUser, 'PAGADO');
                $hojaActiva -> setCellValue('I'.$filaUser, 'ESTADO');

                //COMBINAR CELDAS
            /* $hojaActiva -> mergeCells('A3:E3');
                $hojaActiva -> mergeCells('F3:H3');
                $hojaActiva -> mergeCells('I3:K3');*/

                //PONER NEGRITA A LOS NOMBRES DE LA CELDAS
                $hojaActiva -> getStyle('A'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('B'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('C'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('D'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('E'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('F'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('G'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('H'.$filaUser) -> getFont() -> setBold(true);
                $hojaActiva -> getStyle('I'.$filaUser) -> getFont() -> setBold(true);

                //COLOR DE FONDO Y COLOR DE FUENTE
                $spreadSheet -> getActiveSheet() -> getStyle('A'.$filaUser.':I'.$filaUser) -> getFill()
                    -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill:: FILL_SOLID)
                    -> getStartColor() -> setARGB('D9D9D9');  //Color de fondo
                $spreadSheet -> getActiveSheet() -> getStyle('A'.$filaUser.':I'.$filaUser) -> getFont() -> getColor() -> setRGB('000000'); //Color de texto

                $filaUser=$filaUser+1;
                //ASIGNAR VALORES
                $hojaActiva -> setCellValue('A'.$filaUser, $row -> fecha);
                $hojaActiva -> setCellValue('B'.$filaUser, $row -> cliente);
                $hojaActiva -> setCellValue('C'.$filaUser, $row -> lote);
                $hojaActiva -> setCellValue('D'.$filaUser, $row -> total_venta);
                $hojaActiva -> setCellValue('E'.$filaUser, $row -> intereses);
                $hojaActiva -> setCellValue('F'.$filaUser, $row -> total_financiado);
                $hojaActiva -> setCellValue('G'.$filaUser, $row -> total_pendiente);
                $hojaActiva -> setCellValue('H'.$filaUser, $row -> total_pagado);
                $hojaActiva -> setCellValue('I'.$filaUser, $row -> estado);

                //ESTABLECER MARGENES DE COLUMNAS - INICIO Y FIN
                $hojaActiva -> mergeCells('A'.$filaUser.':B'.$filaUser);
                $hojaActiva -> mergeCells('B'.$filaUser.':C'.$filaUser);
                $hojaActiva -> mergeCells('C'.$filaUser.':D'.$filaUser);
                $hojaActiva -> mergeCells('D'.$filaUser.':E'.$filaUser);
                $hojaActiva -> mergeCells('E'.$filaUser.':F'.$filaUser);
                $hojaActiva -> mergeCells('F'.$filaUser.':G'.$filaUser);
                $hojaActiva -> mergeCells('G'.$filaUser.':H'.$filaUser);
                $hojaActiva -> mergeCells('H'.$filaUser.':I'.$filaUser);
                $hojaActiva -> mergeCells('I'.$filaUser.':J'.$filaUser);

                $filaVisita=$filaUser+1;
                $Codigo = $row -> id;

            }

            


            $listaCronograma = $this -> get_categorias -> VerCronogramaPagosGpro($Codigo);
           
    
    
            // for ($j=0; $j < 3; $j++) { 
            foreach($listaCronograma as $row) {
                // Encabezados para los detalles de actividades
               
				$monto__total = 0;
				$intereses_total = 0;
				$amortizacion_total = 0;
				$capital_total = 0;
				$pagado_total = 0;

                $cantVisitas = count($listaCronograma);  

		        if($j==1){

                    $hojaActiva -> setCellValue('B'.$filaVisita, 'FECHA VCTO');
                    $hojaActiva -> setCellValue('C'.$filaVisita, 'LETRA');
                    $hojaActiva -> setCellValue('D'.$filaVisita, 'MONTO');
                    $hojaActiva -> setCellValue('E'.$filaVisita, 'INTERESES');
                    $hojaActiva -> setCellValue('F'.$filaVisita, 'AMORTIZACION');
                    $hojaActiva -> setCellValue('G'.$filaVisita, 'CAPITAL VIVO');
                    $hojaActiva -> setCellValue('H'.$filaVisita, 'PAGADO');
                    $hojaActiva -> setCellValue('I'.$filaVisita, 'ESTADO');

                    //COLOR DE FONDO Y COLOR DE FUENTE, NEGRITA
                    $hojaActiva -> getStyle('B'.$filaVisita.':I'.$filaVisita) -> getFont() -> setBold(true);
                    $spreadSheet -> getActiveSheet() -> getStyle('A'.$filaVisita.':L'.$filaVisita) -> getFill()
                        -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill:: FILL_SOLID)
                        -> getStartColor() -> setARGB('023161');
                    $spreadSheet -> getActiveSheet() -> getStyle('A'.$filaVisita.':L'.$filaVisita) -> getFont() -> getColor() -> setRGB('FFFFFF');
                    $filaVisita = $filaVisita + 1; 
                }               
    
                // Datos de las actividades
                $hojaActiva -> setCellValue('B'.$filaVisita, $row -> fecha);
                $hojaActiva -> setCellValue('C'.$filaVisita, $row -> letra);
                $hojaActiva -> setCellValue('D'.$filaVisita, $row -> monto);
                $hojaActiva -> setCellValue('E'.$filaVisita, $row -> intereses);
                $hojaActiva -> setCellValue('F'.$filaVisita, $row -> amortizacion);
                $hojaActiva -> setCellValue('G'.$filaVisita, $row -> capital_vivo);
                $hojaActiva -> setCellValue('H'.$filaVisita, $row -> pagado);
                $hojaActiva -> setCellValue('I'.$filaVisita, $row -> descEstado);

                //SUMATORIAS
                $monto__total += $row -> monto;
                $intereses_total += $row -> intereses;
                $amortizacion_total += $row -> amortizacion;
                $capital_total += $row -> capital_vivo;
                $pagado_total += $row -> pagado;
                $filaVisita = $filaVisita + 1;

                if ($cantVisitas == $j) {
                    $hojaActiva -> setCellValue('B'.$filaVisita, 'TOTAL');
                    $hojaActiva -> setCellValue('D'.$filaVisita, $monto__total);
                    $hojaActiva -> setCellValue('E'.$filaVisita, $intereses_total);
                    $hojaActiva -> setCellValue('F'.$filaVisita, $amortizacion_total);
                    $hojaActiva -> setCellValue('G'.$filaVisita, $capital_total);
                    $hojaActiva -> setCellValue('H'.$filaVisita, $pagado_total);

                    $hojaActiva -> mergeCells('B'.$filaVisita.':C'.$filaVisita);
                    $hojaActiva -> mergeCells('E'.$filaVisita.':E'.$filaVisita);
                    $monto__total = 0;
                    $intereses_total = 0;
                    $amortizacion_total = 0;
                    $capital_total = 0;
                    $pagado_total = 0;
                }
                $filaUser = $filaVisita + 1;
                //$filaUser=$filaVisita;
                $j += 1;
            }
            $j = 1;

            if ($cantUser == $i) {
                //$filaUser=$filaUser+1;
                $hojaActiva -> setCellValue('A'.$filaUser, 'TOTAL GENERAL');
                $hojaActiva -> setCellValue('D'.$filaUser, $monto__total);
                $hojaActiva -> setCellValue('E'.$filaUser, $intereses_total);
                $hojaActiva -> setCellValue('F'.$filaUser, $amortizacion_total);
                $hojaActiva -> setCellValue('G'.$filaUser, $capital_total);
                $hojaActiva -> setCellValue('H'.$filaUser, $pagado_total);

                $hojaActiva -> mergeCells('A'.$filaUser.':C'.$filaUser);
                $hojaActiva -> mergeCells('E'.$filaUser.':E'.$filaUser);

                $hojaActiva -> getStyle('A'.$filaUser.':I'.$filaUser) -> getFont() -> setBold(true);
                $spreadSheet -> getActiveSheet() -> getStyle('A'.$filaUser.':I'.$filaUser) -> getFill()
                    -> setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill:: FILL_SOLID)
                    -> getStartColor() -> setARGB('F2F2F2');
                $spreadSheet -> getActiveSheet() -> getStyle('A'.$filaUser.':L'.$filaUser) -> getFont() -> getColor() -> setRGB('000000');
            }

            $i += 1;
        }
        // Establece las anchuras de las columnas automáticamente
        foreach(range('A', 'I') as $column) {
            $hojaActiva -> getColumnDimension($column) -> setAutoSize(true);
        }
    
        $spreadSheet -> getActiveSheet() -> getStyle('D6:D'.$filaUser) -> applyFromArray($style);
        $spreadSheet -> getActiveSheet() -> getStyle('E6:E'.$filaUser) -> applyFromArray($style);
        $spreadSheet -> getActiveSheet() -> getStyle('F6:F'.$filaUser) -> applyFromArray($style);
        $spreadSheet -> getActiveSheet() -> getStyle('G6:G'.$filaUser) -> applyFromArray($style);
        $spreadSheet -> getActiveSheet() -> getStyle('H6:H'.$filaUser) -> applyFromArray($style);
    
    
        /*$yellowStyle = new Style(false, true);
        $yellowStyle -> getFill()
            -> setFillType(Fill:: FILL_SOLID)
            -> getStartColor() -> setARGB('FFF8DD');
        $yellowStyle -> getFill()
            -> getEndColor() -> setARGB('FFF8DD');
        // $yellowStyle->getFont()->setColor(new Color(Color::COLOR_YELLOW));
        $yellowStyle -> getFont() -> getColor() -> setRGB('FFC700');
    
        $greenStyle = new Style(false, true);
        $greenStyle -> getFill()
            -> setFillType(Fill:: FILL_SOLID)
            -> getStartColor() -> setRGB('E8FFF3');
        $greenStyle -> getFill()
            -> getEndColor() -> setRGB('E8FFF3');
        // $greenStyle->getFont()->setColor(new Color(Color::COLOR_DARKGREEN));
        $greenStyle -> getFont() -> getColor() -> setRGB('50CD89');
    
        $redStyle = new Style(false, true);
        $redStyle -> getFill()
            -> setFillType(Fill:: FILL_SOLID)
            -> getStartColor() -> setARGB('FFF5F8');
        $redStyle -> getFill()
            -> getEndColor() -> setARGB('FFF5F8');
        // $redStyle->getFont()->setColor(new Color(Color::COLOR_RED));
        $redStyle -> getFont() -> getColor() -> setRGB('F1416C');
    
        $cellRange = 'L6:L'.$filaVisita;
        $cellRangeEstado2 = 'J6:L'.$filaVisita;
    
        $conditionalStyles = [];
        $wizardFactory = new Wizard($cellRange);
        // @var Wizard\TextValue $textWizard 
        $textWizard = $wizardFactory -> newRule(Wizard:: TEXT_VALUE);
    
        $textWizard -> beginsWith('APROBADO')
            -> setStyle($greenStyle);
        $conditionalStyles[] = $textWizard -> getConditional();
    
        $textWizard -> beginsWith('PENDIENTE')
            -> setStyle($yellowStyle);
        $conditionalStyles[] = $textWizard -> getConditional();
    
        $textWizard -> beginsWith('RECHAZADO')
            -> setStyle($redStyle);
        $conditionalStyles[] = $textWizard -> getConditional();
        $spreadSheet -> getActiveSheet()
            -> getStyle($textWizard -> getCellRange())
            -> setConditionalStyles($conditionalStyles);
    
        $conditionalStyles2 = [];
        $wizardFactory2 = new Wizard($cellRangeEstado2);
        // @var Wizard\TextValue $textWizard 
        $textWizard2 = $wizardFactory2 -> newRule(Wizard:: TEXT_VALUE);
    
        $textWizard -> beginsWith('PAGADO')
            -> setStyle($greenStyle);
        $conditionalStyles2[] = $textWizard2 -> getConditional();
    
        $textWizard -> beginsWith('POR PAGAR')
            -> setStyle($yellowStyle);
        $conditionalStyles2[] = $textWizard2 -> getConditional();
        $spreadSheet -> getActiveSheet()
            -> getStyle($textWizard2 -> getCellRange())
            -> setConditionalStyles($conditionalStyles2);
        */
    
    
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$name.'.xlsx"');
        header('Cache-Control: max-age=0');
    
        $writer = IOFactory:: createWriter($spreadSheet, 'Xlsx');
        $writer -> save('php://output');
    
        exit();

	}



}