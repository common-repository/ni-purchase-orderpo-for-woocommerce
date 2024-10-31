<?php

// reference the Dompdf namespace
use Dompdf\Dompdf;
use Dompdf\Options;

//if (!defined('BASEPATH')) exit('No direct script access allowed');

require("autoload.inc.php");

$options = new Options();

$options->set('defaultFont', 'Courier');

// instantiate and use the dompdf class
$dompdf = new Dompdf($options);

$options->setIsHtml5ParserEnabled(true);