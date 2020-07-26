<?php

require_once 'vendor/autoload.php';
require 'inc/config.php';
require 'inc/certDompdf.php';
require 'inc/certMailer.php';

ini_set('memory_limit', MEMORY_LIMIT);
setlocale(LC_ALL, LOCALE);
date_default_timezone_set('Asia/Kolkata');

// $def_options = [
//     ['i', 'input', \GetOpt\GetOpt::REQUIRED_ARGUMENT, 'HTML template file'],
//     ['o', 'output', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'PDF output file/directory', 'output.pdf'],
//     ['y', 'offset', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Y offset of HTML cell', 0],
//     ['d', 'data', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'CSV file with data to fill template', ''],
//     ['p', 'page', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Page size', 'A4'],
//     ['e', 'email_col', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Email column name in CSV file', 'email'],
//     ['s', 'subject', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Email subject', 'Certificate'],
//     ['m', 'message', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Email message or a path to file with message', 'Here is your certificate'],
//     ['r', 'replyto', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Reply to email'],
//     ['a', 'attach', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Additional attachment'],
//     ['?', 'help', \GetOpt\GetOpt::NO_ARGUMENT, 'Show this help and quit'],
//     ['f', 'font', \GetOpt\GetOpt::OPTIONAL_ARGUMENT, 'Add font to TCPDF'],
// ];
// $getopt = new \GetOpt\GetOpt($def_options);
// try {
//     try {
//         $getopt->process();
//     } catch (Missing $exception) {
//         // catch missing exceptions if help is requested
//         if (!$getopt->getOption('help')) {
//             throw $exception;
//         }
//     }
// } catch (Exception $exception) {
//     file_put_contents('php://stderr', $exception->getMessage().PHP_EOL);
//     echo PHP_EOL.$getopt->getHelpText();
//     exit;
// }

// if ($getopt->getOption('help')) {
//     echo $getopt->getHelpText();
//     exit;
// }
// $options = $getopt->getOptions();

// if (isset($options['f'])) {
//     $font_file = $options['f'];
//     // convert TTF font to TCPDF format and store it on the fonts folder
//     $fontname = TCPDF_FONTS::addTTFfont($font_file);
//     echo 'Font added: '.$fontname."\n";
//     echo "Change DEFAULT_FONT value to {$fontname} in config.php to use the new font!\n";
//     exit;
// }

// if (!isset($options['c']) || !isset($options['o'])) {
//     exit;
// }

// Background image
// $img_file = '';
// if (isset($options['i'])) {
    // $img_file = realpath($options['i']);
// }

// Text
$input_html = file_get_contents('sample.html');

// Page size
$size = 'A4';
if (isset($options['p'])) {
    if (preg_match('/\d+\.*\d*\s*,\s*\d+\.*\d*/', $options['p'])) {
        $size = array_map('floatval', split('\s*,\s*', $options['p']));
    } elseif (is_numeric($options['p'])) {
        $size = [floatval($options['p']), floatval($options['p'])];
    } else {
        $size = $options['p'];
    }
}

// CSV Data file
$data_file = $resRow['csvLocation'];
if (isset($options['d'])) {
    $data_file = $options['d'];
}

// Output file/directory
$output = 'output/';
if (is_dir($output)) {
    $output = realpath(rtrim($output, '/\\'));
}

$y_offset = 0;
if (isset($options['y'])) {
    $y_offset = floatval($options['y']);
}

// Send PDF by email
$email_col_name = 'email';
$send_by_email = false;
if (isset($options['e'])) {
    $email_col_name = $options['e'];
}

$email_subject = 'Certificate of participation';
if (isset($options['s'])) {
    $email_subject = $options['s'];
}
$email_message='';
if (isset($options['m'])) {
    if (is_file($options['m'])) {
        $email_message = file_get_contents($options['m']);
    } else {
        $email_message = $options['m'];
    }
}

if (isset($options['r'])) {
    if (preg_match('/(.*);(.*@.*)/', $options['r'], $matches)) {
        $email_from_name = $matches[1];
        $email_from = $matches[2];
    } else {
        $email_from = $options['r'];
        $email_from_name = $email_from;
    }
} else {
    $email_from = MAIL_USERNAME;
    $email_from_name = MAIL_USERNAME;
}

// Get any email attchament
$attchments = [];
if (isset($options['a'])) {
    $attchments = explode(',', $options['a']);
}

if (!empty($data_file)) {
    if (false === is_dir($output)) {
        echo "Output is not a directory. Please, provide a directory to output PDF's\n";
        exit;
    }

    if (false !== ($handle = fopen($data_file, 'r'))) {
        $csv_header = fgetcsv($handle, 1000, DELIMITER);
        $send_by_email = in_array($email_col_name, $csv_header);
        $i = 0;
        $mailer = new CertMailer();
        while (false !== ($data = fgetcsv($handle, 1000, DELIMITER))) {
            if (count($data) > 0) {
                $row = [];
                foreach ($data as $key => $value) {
                    $row[trim($csv_header[$key])] = preg_replace('/\x{FEFF}/u', '', $value);
                }
                print_r($row);

                $output_file = isset($row[$email_col_name]) ? $output.DIRECTORY_SEPARATOR.strtolower(trim($row[$email_col_name])).'_'.$resRow['id'].'_'.$resRow['eventName'].'.pdf' : $output.DIRECTORY_SEPARATOR.$i.'_'.$resRow['id'].'_'.$resRow['eventName'].'.pdf';
                // create new PDF document
                $pdf = new CERTIFICATEDOMPDF('landscape', 'cm', $size, true, 'UTF-8', false, false);
                $pdf->create_pdf($output_file, $input_html, $y_offset, $row, DATE_FORMAT);
                unset($pdf);
                if ($send_by_email && file_exists($output_file)) {
                    $email_to = $row[$email_col_name];
                    $email_body = $email_message;
                    foreach ($row as $key => $value) {
                        $email_body = preg_replace('/\{\{\s*'.$key.'\s*\}\}/', trim($value), $email_body);
                    }
                    $email_body = str_replace('{{ %now% }}', strftime(DATE_FORMAT), $email_body);

                    //$mailer->send_mail($email_to, $email_subject, $email_body, $email_from, $email_from_name, $output_file, $attchments);
                }
                ++$i;
            }
        }

        fclose($handle);

        $gen=mysqli_query($conn,"update workshopEvent set genrated='YES' where id=".$resRow['id']);
        if($gen){
            echo "<script>window.location.href='index.php'</script>";
        }
        else{
            echo mysqli_error($conn);
        }


    }
} 
else {
    $pdf = new CERTIFICATEPDF('L', 'cm', $size, true, 'UTF-8', false, false);
    $pdf->create_pdf($output_file, $input_html, $y_offset, DATE_FORMAT);
}
