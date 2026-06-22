<?php

class api_facturacion
{
    //envio de comprobantes: BOLETA, FACTURA, NOTA DE CREDITO, NOTA DE DEBITO (SENDBILL)
    public function enviar_comprobante($emisor, $nombreXML, $ruta_certificado, $ruta_archivo_xml, $ruta_archivo_cdr)
    {
        $estado_envio = 0;

        //FIRMAR DIGITALMENTE EL XML
        require_once("../signature.php");
        $objFirma = new Signature();
        $flgFirma = 0; //posicion de la firma en el XML
        $ruta_certificado = $ruta_certificado . 'certificado_prueba.pfx';
        $pass_certificado = 'ceti';
        //$ruta_certificado = $ruta_certificado . 'CERTIFICADO-31D0110EFB7839D489.pfx';
        //$pass_certificado = 'y1PZ6BxVHFXbVt8';
        $ruta_xml = $ruta_archivo_xml . $nombreXML . '.XML';

        $resp_hash = $objFirma->signature_xml($flgFirma, $ruta_xml, $ruta_certificado, $pass_certificado);
        $estado_envio_mensaje = "XML FIRMADO DIGITALMENTE";
        $estado_envio = 1;


        //COMPRIMIR EN ZIP
        $zip = new ZipArchive();
        $ruta_zip = $ruta_archivo_xml . $nombreXML . '.ZIP';
        if ($zip->open($ruta_zip, ZipArchive::CREATE) == TRUE) {
            $zip->addFile($ruta_xml, $nombreXML . '.XML');
            $zip->close();
        }
        $estado_envio_mensaje = "XML COMPRIMIDO EN ZIP";
        $estado_envio = 2;

        //CODIFICAR EN BASE64
        $zip_codificado = base64_encode(file_get_contents($ruta_zip));
        $estado_envio_mensaje = "ZIP CODIFICADO EN BASE 64 :" . $zip_codificado;
        $estado_envio = 3;

        //CONSUMIR LOS WEB SERVICES DE SUNAT

        $url_ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
        //$url_ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService"; //produccion

        $filename_zip = $nombreXML . '.ZIP';


        
        date_default_timezone_set('UTC'); // asegurar UTC

$created = gmdate('Y-m-d\TH:i:s\Z');
$expires = gmdate('Y-m-d\TH:i:s\Z', time() + 300); // 5 min después
$nonce = base64_encode(random_bytes(16)); // nonce aleatorio

$xml_envelope = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
    xmlns:ser="http://service.sunat.gob.pe"
    xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd"
    xmlns:wsu="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd">
    <soapenv:Header>
        <wsse:Security soapenv:mustUnderstand="1">
            <wsu:Timestamp wsu:Id="Timestamp-1">
                <wsu:Created>' . $created . '</wsu:Created>
                <wsu:Expires>' . $expires . '</wsu:Expires>
            </wsu:Timestamp>
            <wsse:UsernameToken wsu:Id="UsernameToken-1">
                <wsse:Username>' . $emisor['nrodoc'] . $emisor['usuario_secundario'] . '</wsse:Username>
                <wsse:Password>' . $emisor['clave_usuario_secundario'] . '</wsse:Password>
                <wsse:Nonce>' . $nonce . '</wsse:Nonce>
                <wsu:Created>' . $created . '</wsu:Created>
            </wsse:UsernameToken>
        </wsse:Security>
    </soapenv:Header>
    <soapenv:Body>
        <ser:sendBill>
            <fileName>' . $filename_zip . '</fileName>
            <contentFile>' . $zip_codificado . '</contentFile>
        </ser:sendBill>
    </soapenv:Body>
</soapenv:Envelope>';


        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url_ws);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envelope);

        // $output contains the output string
        $output = curl_exec($ch); //ejecutamos y obtenemos el contenido de la rpta de sunat
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); //obtenemos el codigo de rpta

        //IMPORTANTE: EL OUTPUT SE DEBE GUARGAR EN BD O FILESYSTEM

        $estado_envio_mensaje = "CONSUMO DEL WEB SERVICE DE SUNAT";
        $estado_envio = 4;

        //RESPUESTA /RECEPCION DEL WS
        $descripcion = "";
        $nota = "";
        $codigo = "";
        $mensaje = "";
        
        if ($http_code == 200) { //OK HUBO RPTA DE SUNAT
            $doc = new DOMDocument();
            $doc->loadXML($output); //Convertimos en XML y cargamos la rpta de SUNAT

            if (isset($doc->getElementsByTagName("applicationResponse")->item(0)->nodeValue)) {
                $estado_envio_mensaje = "CDR - SUNAT APROBO EL COMPROBANTE";
                $estado_envio = 5;

                $cdr = $doc->getElementsByTagName("applicationResponse")->item(0)->nodeValue;

                //DECODIFICAR EN BASE64 EL CDR (OBTENEMOS EL .ZIP)
                $cdr = base64_decode($cdr);
                $estado_envio_mensaje = "CDR - DECODIFICADO, OBTENEMOS EL ZIP";
                $estado_envio = 6;

                //COPIAR DE MEMORIA A DISCO EL ZIP
                file_put_contents($ruta_archivo_cdr . 'R-' . $filename_zip, $cdr);
                $estado_envio_mensaje = "CDR EN ZIP COPIADO A DISCO";
                $estado_envio = 7;

                //EXTRAER EL ZIP
                $zip = new ZipArchive();
                if ($zip->open($ruta_archivo_cdr . 'R-' . $filename_zip) == TRUE) {
                    $zip->extractTo($ruta_archivo_cdr);
                    $zip->close();

                    $xml_cdr = $ruta_archivo_cdr . 'R-' . $nombreXML . '.XML';
                    $doc_cdr = new DOMDocument();
                    $doc_cdr->loadXML(file_get_contents($xml_cdr));
                    if (isset($doc_cdr->getElementsByTagName("Description")->item(0)->nodeValue)) {
                        $descripcion = $doc_cdr->getElementsByTagName("Description")->item(0)->nodeValue;
                    }

                    if (isset($doc_cdr->getElementsByTagName("Note")->item(0)->nodeValue)) {
                        $nota = $doc_cdr->getElementsByTagName("Note")->item(0)->nodeValue;
                    }

                    $estado_envio_mensaje = "PROCESO TERMINADO";
                    $estado_envio = 8;
                }
            }else{
                $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                $estado_envio_mensaje = "ERROR/RECHAZO DE SUNAT";
                $estado_envio = 9;
            }
        }else{
            curl_error($ch);
            $estado_envio_mensaje = "ERROR EN EL CONSUMO DEL WS/RED/CONEXION";
            $estado_envio = 10;

            $doc = new DOMDocument();
            $doc->loadXML($output);
            $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
            $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;

            $output = "ERROR EN CONSUMO DE WS/RED/CONEXION: " . $output;
        }


        // close curl resource to free up system resources

        curl_close($ch);

        $estado_envio = array(
            'estado'            =>  $estado_envio,
            'estado_mensaje'    =>  $estado_envio_mensaje,
            'hash_cpe'          =>  $resp_hash['hash_cpe'],
            'descripcion'       =>  $descripcion,
            'nota'              =>  $nota,
            'codigo_error'      =>  str_replace('soap-env:Client.', '',  $codigo),
            'mensaje_error'     =>  $mensaje,
            'http_code'         =>  $http_code,
            'output'            =>  $output
        );

        return $estado_envio;
    }

    public function enviar_resumen($emisor, $nombreXML, $ruta_certificado, $ruta_archivo_xml)
    {
        $estado_envio = 0;

        //FIRMAR DIGITALMENTE EL XML
        require_once("../signature.php");
        $objFirma = new Signature();
        $flgFirma = 0; //posicion de la firma en el XML
        $ruta_certificado = $ruta_certificado . 'certificado_prueba.pfx';
        $pass_certificado = 'ceti';
        $ruta_xml = $ruta_archivo_xml . $nombreXML . '.XML';

        $resp_hash = $objFirma->signature_xml($flgFirma, $ruta_xml, $ruta_certificado, $pass_certificado);
        $estado_envio_mensaje = "XML FIRMADO DIGITALMENTE";
        $estado_envio = 1;


        //COMPRIMIR EN ZIP
        $zip = new ZipArchive();
        $ruta_zip = $ruta_archivo_xml . $nombreXML . '.ZIP';
        if ($zip->open($ruta_zip, ZipArchive::CREATE) == TRUE) {
            $zip->addFile($ruta_xml, $nombreXML . '.XML');
            $zip->close();
        }
        $estado_envio_mensaje = "XML COMPRIMIDO EN ZIP";
        $estado_envio = 2;

        //CODIFICAR EN BASE64
        $zip_codificado = base64_encode(file_get_contents($ruta_zip));
        $estado_envio_mensaje = "ZIP CODIFICADO EN BASE 64 :" . $zip_codificado;
        $estado_envio = 3;

        //CONSUMIR LOS WEB SERVICES DE SUNAT

        $url_ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
        //$url_ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService"; //produccion

        $filename_zip = $nombreXML . '.ZIP';

        
        $xml_envelope = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken>
                        <wsse:Username>' . $emisor['nrodoc'] . $emisor['usuario_secundario'] . '</wsse:Username>
                        <wsse:Password>' . $emisor['clave_usuario_secundario'] . '</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:sendSummary>
                    <fileName>' . $filename_zip . '</fileName>
                    <contentFile>' . $zip_codificado . '</contentFile>
                </ser:sendSummary>
            </soapenv:Body>
        </soapenv:Envelope>';

        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, $url_ws);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envelope);

        // $output contains the output string
        $output = curl_exec($ch); //ejecutamos y obtenemos el contenido de la rpta de sunat
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); //obtenemos el codigo de rpta

        //IMPORTANTE: EL OUTPUT SE DEBE GUARGAR EN BD O FILESYSTEM

        $estado_envio_mensaje = "CONSUMO DEL WEB SERVICE DE SUNAT";
        $estado_envio = 4;

        //RESPUESTA /RECEPCION DEL WS
        $descripcion = "";
        $nota = "";
        $codigo = "";
        $mensaje = "";
        $ticket = "";

        if ($http_code == 200) {
            $doc = new DOMDocument();
            $doc->loadXML($output);

            if (isset($doc->getElementsByTagName("ticket")->item(0)->nodeValue)) {
                $ticket = $doc->getElementsByTagName("ticket")->item(0)->nodeValue;
                $estado_envio_mensaje = "SE OBTUVO NRO DE TICKET: " . $ticket;
                $estado_envio = 5;
            }else{
                $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                $estado_envio_mensaje = "ERROR/RECHAZO DE SUNAT";
                $estado_envio = 9;
            }
        }else{
            curl_error($ch);
            $estado_envio_mensaje = "ERROR EN EL CONSUMO DEL WS/RED/CONEXION";
            $estado_envio = 10;

            $doc = new DOMDocument();
            $doc->loadXML($output);
            $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
            $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;

            $output = "ERROR EN CONSUMO DE WS/RED/CONEXION: " . $output;
        }

        // close curl resource to free up system resources

        curl_close($ch);

        $estado_envio = array(
            'estado'            =>  $estado_envio,
            'estado_mensaje'    =>  $estado_envio_mensaje,
            'hash_cpe'          =>  $resp_hash['hash_cpe'],
            'descripcion'       =>  $descripcion,
            'nota'              =>  $nota,
            'codigo_error'      =>  str_replace('soap-env:Client.', '',  $codigo),
            'mensaje_error'     =>  $mensaje,
            'http_code'         =>  $http_code,
            'output'            =>  $output,
            "ticket"            =>  $ticket
        );

        return $estado_envio;
    }

    public function consultar_ticket($emisor, $cabecera, $ticket, $ruta_archivo_cdr = 'cdr/')
    {
        //CONSUMIR LOS WEB SERVICES DE SUNAT

        $url_ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
        //$url_ws = "https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService"; //produccion
       
        $xml_envelope = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/"
        xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasisopen.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken>
                        <wsse:Username>' . $emisor['nrodoc'] . $emisor['usuario_secundario'] . '</wsse:Username>
                        <wsse:Password>' . $emisor['clave_usuario_secundario'] . '</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:getStatus>
                    <ticket>' . $ticket . '</ticket>
                </ser:getStatus>
            </soapenv:Body>
        </soapenv:Envelope>';

         //curl para consumir servicios
         //crear el servicio
         $ch = curl_init();
 
         //setear los parametros
         curl_setopt($ch, CURLOPT_URL, $url_ws);//indicamos la ruta del web service de sunat
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envelope); //enviamso el XML envelope con el metodo POST
 
         //Ejecutamos el servicio y obtenemos la respuesta de sunat
         $output = curl_exec($ch);
 
         //obejte el codigo HTTP de respuesta
         $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
         $estado_envio = 4;
         $estado_envio_mensaje = "CONSUMO DEL WEB SERVICES DE SUNAT";
 
         //RESPUESTA O REPECION DE WS
         $descripcion = ""; //mensjae de sunat dentro del xml de rpta
         $nota = ""; //mensaje de sunat, indica alguna obser
         $codigo = ""; //mensaje de sunat, para indicar el codigo de error
         $mensaje = ""; //mensaje de sunat, para indicar el mensaje de error

         $nombreXML = $emisor['nrodoc'] . '-' . $cabecera['tipodoc'] . '-' . $cabecera['serie'] . '-' . $cabecera['correlativo'];
         $filename_zip = $nombreXML . '.ZIP';

         if ($http_code == 200) { //OK HUBO RPTA DE SUNAT
            $doc = new DOMDocument();
            $doc->loadXML($output); //Convertimos en XML y cargamos la rpta de SUNAT

            if (isset($doc->getElementsByTagName("content")->item(0)->nodeValue)) {
                $estado_envio_mensaje = "CDR - SUNAT APROBO EL COMPROBANTE";
                $estado_envio = 5;

                $cdr = $doc->getElementsByTagName("content")->item(0)->nodeValue;

                //DECODIFICAR EN BASE64 EL CDR (OBTENEMOS EL .ZIP)
                $cdr = base64_decode($cdr);
                $estado_envio_mensaje = "CDR - DECODIFICADO, OBTENEMOS EL ZIP";
                $estado_envio = 6;

                //COPIAR DE MEMORIA A DISCO EL ZIP
                file_put_contents($ruta_archivo_cdr . 'R-' . $filename_zip, $cdr);
                $estado_envio_mensaje = "CDR EN ZIP COPIADO A DISCO";
                $estado_envio = 7;

                //EXTRAER EL ZIP
                $zip = new ZipArchive();
                if ($zip->open($ruta_archivo_cdr . 'R-' . $filename_zip) == TRUE) {
                    $zip->extractTo($ruta_archivo_cdr);
                    $zip->close();

                    $xml_cdr = $ruta_archivo_cdr . 'R-' . $nombreXML . '.XML';
                    $doc_cdr = new DOMDocument();
                    $doc_cdr->loadXML(file_get_contents($xml_cdr));
                    if (isset($doc_cdr->getElementsByTagName("Description")->item(0)->nodeValue)) {
                        $descripcion = $doc_cdr->getElementsByTagName("Description")->item(0)->nodeValue;
                    }

                    if (isset($doc_cdr->getElementsByTagName("Note")->item(0)->nodeValue)) {
                        $nota = $doc_cdr->getElementsByTagName("Note")->item(0)->nodeValue;
                    }

                    $estado_envio_mensaje = "PROCESO TERMINADO";
                    $estado_envio = 8;
                }
            }else{
                $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
                $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
                $estado_envio_mensaje = "ERROR/RECHAZO DE SUNAT";
                $estado_envio = 9;
            }
        }else{
            curl_error($ch);
            $estado_envio_mensaje = "ERROR EN EL CONSUMO DEL WS/RED/CONEXION";
            $estado_envio = 10;

            $doc = new DOMDocument();
            $doc->loadXML($output);
            $codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
            $mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;

            $output = "ERROR EN CONSUMO DE WS/RED/CONEXION: " . $output;
        }


        // close curl resource to free up system resources

        curl_close($ch);

        $estado_envio = array(
            'estado'            =>  $estado_envio,
            'estado_mensaje'    =>  $estado_envio_mensaje,
            'descripcion'       =>  $descripcion,
            'nota'              =>  $nota,
            'codigo_error'      =>  str_replace('soap-env:Client.', '',  $codigo),
            'mensaje_error'     =>  $mensaje,
            'http_code'         =>  $http_code,
            'output'            =>  $output
        );

        return $estado_envio;

    }

    function consultarComprobante($emisor, $comprobante)
    {
		try{
            $ws = "https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService";
            $soapUser = "";  
            $soapPassword = "";

            $xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
            xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" 
            xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
                <soapenv:Header>
                    <wsse:Security>
                        <wsse:UsernameToken>
                            <wsse:Username>'.$emisor['ruc'].$emisor['usuariosol'].'</wsse:Username>
                            <wsse:Password>'.$emisor['clavesol'].'</wsse:Password>
                        </wsse:UsernameToken>
                    </wsse:Security>
                </soapenv:Header>
                <soapenv:Body>
                    <ser:getStatus>
                        <rucComprobante>'.$emisor['ruc'].'</rucComprobante>
                        <tipoComprobante>'.$comprobante['tipodoc'].'</tipoComprobante>
                        <serieComprobante>'.$comprobante['serie'].'</serieComprobante>
                        <numeroComprobante>'.$comprobante['correlativo'].'</numeroComprobante>
                    </ser:getStatus>
                </soapenv:Body>
            </soapenv:Envelope>';
        
            $headers = array(
                "Content-type: text/xml;charset=\"utf-8\"",
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Pragma: no-cache",
                "SOAPAction: ",
                "Content-length: " . strlen($xml_post_string),
            ); 			
        
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $ws);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
            //para ejecutar los procesos de forma local en windows
            //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            echo var_dump($response);
            
        } catch (Exception $e) {
            echo "SUNAT ESTA FUERA SERVICIO: ".$e->getMessage();
        }
    }
}

?>