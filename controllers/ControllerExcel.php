<?php

require_once("../config/conexion.php");
require_once("../models/excel/vendor/autoload.php");

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

// Conexión PDO 
$pdo = Conexion::Conectar();

if (isset($_POST['btnExportarExcel'])) {
    // Filtros del formulario
    $txtFiltroDocumentoCP = $_POST['txtFiltroDocumentoCP'];
    $bxFiltroProyectoCP   = $_POST['bxFiltroProyectoCP'];
    $bxFiltroZonaCP       = $_POST['bxFiltroZonaCP'];
    $bxFiltroManzanaCP    = $_POST['bxFiltroManzanaCP'];
    $bxFiltroLoteCP       = $_POST['bxFiltroLoteCP'];
    $bxFiltroEstadoCP     = $_POST['bxFiltroEstadoCP'];

    $query_documento = !empty($txtFiltroDocumentoCP) ? "AND cli.documento = '$txtFiltroDocumentoCP'" : "";
    $query_proyecto  = !empty($bxFiltroProyectoCP)   ? "AND gpy.idproyecto = '$bxFiltroProyectoCP'"   : "";
    $query_zona      = !empty($bxFiltroZonaCP)       ? "AND gpz.idzona = '$bxFiltroZonaCP'"           : "";
    $query_manzana   = !empty($bxFiltroManzanaCP)    ? "AND gpm.idmanzana = '$bxFiltroManzanaCP'"     : "";
    $query_lote      = !empty($bxFiltroLoteCP)       ? "AND gpl.idlote = '$bxFiltroLoteCP'"           : "";
    $query_estado    = !empty($bxFiltroEstadoCP)     ? "AND gpv.cancelado = '$bxFiltroEstadoCP'"      : "";
	
    // Crear archivo
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("Ventas");

    
	$logo = new Drawing();
    $logo->setPath('../images/gprosac.png');
    $logo->setCoordinates('A1');
    $logo->setOffsetX(30);
    $logo->setOffsetY(5);
    $logo->setWidthAndHeight(34, 34);
    $logo->getShadow()->setVisible(true)->setDirection(45);
    $logo->setWorksheet($sheet);
    
	
	$sheet->setCellValue('A1','REPORTE DE VENTAS');
    $sheet->mergeCells('A1:I1');
    $sheet->getStyle('A1:I1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
    // Datos
    $fila = 3;
	
	// Consulta cabecera
    $sql = "SELECT
		gpv.id_venta AS id,
        gpv.fecha_venta AS fecha,
        CONCAT(cli.apellido_paterno,' ',cli.apellido_materno,' ',cli.nombres) AS cliente,
        CONCAT(SUBSTRING(gpm.nombre,9,2), ' - ',SUBSTRING(gpl.nombre,6,2)) AS lote,
        FORMAT(gpv.total, 2) AS total_venta,
        FORMAT((SELECT SUM(gpc.monto_letra) FROM gp_cronograma gpc WHERE gpc.id_venta = gpv.id_venta) - gpv.total, 2) AS intereses,
        FORMAT((SELECT SUM(gpc.monto_letra) FROM gp_cronograma gpc WHERE gpc.id_venta = gpv.id_venta), 2) AS total_financiado,
        FORMAT((SELECT SUM(gppd.pagado) FROM gp_pagos_detalle gppd WHERE gppd.id_venta = gpv.id_venta), 2) AS total_pagado,
        FORMAT((SELECT SUM(gpc.monto_letra) FROM gp_cronograma gpc WHERE gpc.id_venta = gpv.id_venta) -
               (SELECT SUM(gppd.pagado) FROM gp_pagos_detalle gppd WHERE gppd.id_venta = gpv.id_venta), 2) AS total_pendiente,
        IF(gpv.cancelado = '1', 'CANCELADO', 'ACTIVO') AS estado,
		if(gpv.cancelado='1','red','green') as color_estado
        FROM datos_cliente cli
        INNER JOIN gp_venta gpv ON gpv.id_cliente = cli.id
        INNER JOIN gp_lote gpl ON gpl.idlote = gpv.id_lote
        INNER JOIN gp_manzana gpm ON gpm.idmanzana = gpl.idmanzana
        INNER JOIN gp_zona gpz ON gpz.idzona = gpm.idzona
        INNER JOIN gp_proyecto gpy ON gpy.idproyecto = gpz.idproyecto
        WHERE gpv.estado = '1' 
        AND gpv.conformidad = '1' 
        AND cli.esta_borrado = '0'
        $query_documento
        $query_proyecto
        $query_zona
        $query_manzana
        $query_lote
        $query_estado
        ORDER BY cli.apellido_paterno ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
	
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		
		// Cabeceras
		$headers = ["FECHA VENTA", "CLIENTE", "LOTE", "TOTAL VENTA", "INTERESES", "TOTAL FINANCIADO", "TOTAL PAGADO", "TOTAL PENDIENTE", "ESTADO"];
        $col = 'A';
		
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $fila, $header);
            $col++;
        }
        $sheet->getStyle("A$fila:I$fila")->getFont()->setBold(true);
        $sheet->getStyle("A$fila:I$fila")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('D9D9D9');
        $fila++;
		
        $sheet->setCellValue("A$fila", $row['fecha']);
        $sheet->setCellValue("B$fila", $row['cliente']);
        $sheet->setCellValue("C$fila", $row['lote']);
        $sheet->setCellValue("D$fila", $row['total_venta']);
        $sheet->setCellValue("E$fila", $row['intereses']);
        $sheet->setCellValue("F$fila", $row['total_financiado']);
        $sheet->setCellValue("G$fila", $row['total_pagado']);
        $sheet->setCellValue("H$fila", $row['total_pendiente']);
        $sheet->setCellValue("I$fila", $row['estado']);
		
		$colorEstado = $row['color_estado']; // 'green' o 'red'
		$sheet->getStyle("I$fila")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($colorEstado);
		$sheet->getStyle("I$fila")->getFont()->getColor()->setRGB('FFFFFF');
        $fila++;
		
		/******************* DETALLE **************************/
		$id_venta = $row['id'];
		  
		$query_detalle = "SELECT 
			gpcp.id as id,
			date_format(gpcp.fecha_vencimiento, '%d/%m/%Y') as fecha,	
			gpcp.item_letra as letra,
			format(gpcp.monto_letra,2) as monto,
			format(gpcp.interes_amortizado,2) as intereses,
			format(gpcp.capital_amortizado,2) as amortizacion,
			format(gpcp.capital_vivo,2) as capital_vivo,
			format(gpcp.monto_letra,2) as pagado,
			gpcp.estado as estado,
			cd.nombre_corto as descEstado,
			cd.texto1 as color
			FROM gp_cronograma gpcp
			INNER JOIN configuracion_detalle AS cd ON cd.codigo_item=gpcp.estado AND cd.codigo_tabla='_ESTADO_EC'
			INNER JOIN gp_venta AS gpv ON gpv.id_venta=gpcp.id_venta
			INNER JOIN datos_cliente AS dc ON dc.id=gpv.id_cliente
			WHERE gpcp.esta_borrado=0 AND gpv.id_venta='$id_venta'
			ORDER BY gpcp.correlativo ASC";

		$detalleStmt = $pdo->prepare($query_detalle);
        $detalleStmt->execute();
		
		
		if ($detalleStmt->rowCount() > 0) {
			// Sub-encabezado detalle
			$sheet->setCellValue("A$fila", "ITEM");
			$sheet->setCellValue("B$fila", "FECHA");
			$sheet->setCellValue("C$fila", "LETRA");
			$sheet->setCellValue("D$fila", "MONTO LETRA");
			$sheet->setCellValue("E$fila", "INTERESES");
			$sheet->setCellValue("F$fila", "AMORTIZACION");
			$sheet->setCellValue("G$fila", "CAPITAL VIVO");
			$sheet->setCellValue("H$fila", "TOTAL PAGADO");
			$sheet->setCellValue("I$fila", "ESTADO");			
			
			$sheet->getStyle("A$fila:I$fila")->getFont()->setBold(true);
			$sheet->getStyle("A$fila:I$fila")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('023161');
			$sheet->getStyle("A$fila:I$fila")->getFont()->getColor()->setRGB('FFFFFF');
			$fila++;
			
			$item = 1; // Correlativo
			while ($d = $detalleStmt->fetch(PDO::FETCH_ASSOC)) {
				$sheet->setCellValue("A$fila", $item);
				$sheet->setCellValue("B$fila", $d['fecha']);
				$sheet->setCellValue("C$fila", $d['letra']);
				$sheet->setCellValue("D$fila", $d['monto']);
				$sheet->setCellValue("E$fila", $d['intereses']);
				$sheet->setCellValue("F$fila", $d['amortizacion']);
				$sheet->setCellValue("G$fila", $d['capital_vivo']);
				$sheet->setCellValue("H$fila", $d['pagado']);
				$sheet->setCellValue("I$fila", $d['descEstado']);
				// Color solo al texto del estado
				$colorHex = ltrim($d['color'], '#'); // Eliminar "#"

				if (!empty($colorHex)) {
					$sheet->getStyle("I$fila")->getFont()->getColor()->setRGB($colorHex);
				}

				
				$item++;
				$fila++;  
			}
		}
		/******************* DETALLE **************************/
		
    }
	
    // Auto size
    foreach (range('A', 'I') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    ob_end_clean();
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="ReporteVentas.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save('php://output');
    exit();
}
?>
