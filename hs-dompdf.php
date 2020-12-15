<?php

//require_once('tcpdf_include.php');
//require_once('tcpdf_min/tcpdf.php');
require_once 'dompdf1/autoload.inc.php';
// create new PDF document
use Dompdf\Dompdf;
// ---------------------------------------------------------


// print a block of text using Write()
//$pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);



$html = '<div style="font-family: helvetica, arial; font-size: 13px;"><table style="width: 100%">';

$html .= '<tr>';
$html .= '<td>';
$html .= $from;
$html .= '</td>';
$html .= '<td>'.($to).'</td>';
$html .= '</tr>';
$html .= '<tr><td>&nbsp;</td></tr>';
$html .= '<tr>';
$html .= '<td><b>Invoice No:</b> '.$invoiceNumber.'<br/> <b>Invoice Date:</b> '.$invoiceDate.'</td>';
$html .= '<td><b>Due:</b> '.$dueDate.'</td>';
$html .= '</tr>';
$html .= '</table>';
$html .= "<br />";
$html .= "<br />";
$html .= "<br />";


$html .= '<table style="width: 100%" cellpadding="6" cellspacing="0">';


//$html .= '<tr style="background-color: #F5F5F5;">';

$html .= $headingRow;

$html .= $items;

$html .= $totalsRow;


$html .= '</table>';





$html .= '</div>';
$dompdf = new Dompdf();
$options = $dompdf->getOptions();
$options->setDefaultFont('Helvetica');
$dompdf->setOptions($options);

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4');

// Render the HTML as PDF
$dompdf->render();
//echo $html;
// Output the generated PDF to Browser
$dompdf->stream("invoice-".$invoiceNumber.'-'.$invoiceDateRaw);

