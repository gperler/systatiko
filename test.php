<?php
namespace test;

use Systatiko\Reader\PHPClassScanner;

require 'vendor/autoload.php';


$classScanne = new PHPClassScanner();

$classList = $classScanne->getDefinedPhpClasses(
    file_get_contents(__DIR__ . '/tests/End2End/Asset/Component1/Model/CustomAnnotationClass.php')
);


echo json_encode($classList, JSON_PRETTY_PRINT);


//$src = file_get_contents(__DIR__ . '/tests/End2End/Asset/Component1/Model/CustomAnnotationClass.php');
//
//$tokens = token_get_all($src);
//$count = count($tokens);
//$i = 0;
//$namespace = '';
//$namespace_ok = false;
//while ($i < $count) {
//    $token = $tokens[$i];
//    if (is_array($token) && $token[0] === T_NAMESPACE) {
//        // Found namespace declaration
//        while (++$i < $count) {
//            if ($tokens[$i] === ';') {
//                $namespace_ok = true;
//                $namespace = trim($namespace);
//                break;
//            }
//            $namespace .= is_array($tokens[$i]) ? $tokens[$i][1] : $tokens[$i];
//        }
//        break;
//    }
//    $i++;
//}
//if (!$namespace_ok) {
//    return null;
//} else {
//   echo $namespace;
//}