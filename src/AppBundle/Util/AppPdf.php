<?php

namespace AppBundle\Util;

use TCPDF;
use AppBundle\Entity\Crep;
use AppBundle\Entity\Crep\CrepMindef01\CrepMindef01;
use TCPDF_STATIC;
use AppBundle\Entity\Crep\CrepScl\CrepScl;
use AppBundle\Entity\Crep\CrepScl02\CrepScl02;
use AppBundle\Entity\Crep\CrepMinefAbc\CrepMinefAbc;
use AppBundle\Entity\Crep\CrepMinefContract\CrepMinefContract;
use AppBundle\Entity\Crep\CrepAc\CrepAc;
use AppBundle\Entity\Crep\CrepMcc\CrepMcc;
use AppBundle\Entity\Crep\CrepMso3\CrepMso3;
use AppBundle\Entity\Crep\CrepMj01\CrepMj01;
use AppBundle\Entity\Crep\CrepMcc02\CrepMcc02;
use AppBundle\Entity\Crep\CrepEdd\CrepEdd;
use AppBundle\Entity\Crep\CrepMj02\CrepMj02;
use AppBundle\Entity\Crep\CrepDgac\CrepDgac;
use AppBundle\Entity\Crep\CrepMso5\CrepMso5;

class AppPdf extends TCPDF
{
    private $header = '';

    private $footer = '';

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        //$this->SetFont('helvetica', 'I', 8); // Le font style italic et autres doivent être gérés dans chaque CREP

        $this->writeHTML($this->footer);
    }

    public function initFooter(Crep $crep, $anneeEvaluation)
    {
        if ($crep instanceof CrepMindef01) {
            
            $signatureAgent = '';
            
            if($crep->getDateNotification()){
                $signatureAgent = 'Signé par l\'agent le '. $crep->getDateNotification()->format('d/m/Y H:i:s');
            }
            
            $this->footer = '<table>
            		  <tr>
            		      <td style="width:33%"></td>
            		      <td style="width:34%" align="center">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
            		      <td style="width:33%; font-size: 10px;" align="right"><b><i>'.$signatureAgent.'</i></b></td>
            		  </tr>
            	  </table>';
        } elseif ($crep instanceof CrepMcc) {
            $this->footer = '<table>
            		  <tr>
            		      <td style="width:33%"></td>
            		      <td style="width:34%" align="center">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
            		      <td style="width:33%; font-size: 10px;" align="right"><b>Paraphe : </b></td>
            		  </tr>
            	  </table>';
        } elseif ($crep instanceof CrepScl) { // CrepScl
            $this->footer = '<table style="border:1px #000000 solid;">
            		  <tr>
						<td style="width:20%; border:1px #000000 solid;">FO-RH-17</td>
						<td style="width:15%; border:1px #000000 solid;" align="center">version 02</td>
        				<td style="width:30%; border:1px #000000 solid;" align="center">Date d\'application : 01-08-2014</td>
            		    <td style="width:15%; border:1px #000000 solid;" align="center">page : ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
            		    <td style="width:20%; border:1px #000000 solid;" align="center">Référence : IN-RH-07</td>
            		  </tr>
            	  </table>';
        } elseif ($crep instanceof CrepScl02) { // CrepScl02

            $this->footer = '<table>
            		  <tr>
            		    <td style="width:100%;" align="center">Page ' . $this->getAliasNumPage() . ' sur ' . $this->getAliasNbPages() . '</td>
            		  </tr>
            	  </table>';
        } elseif ($crep instanceof CrepMinefAbc) { // CrepMinefAbc
            $this->footer = '<table>
                        		  <tr>
                        		      <td style="width:100%" align="right">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
                        		  </tr>
                	         </table>';
        } elseif ($crep instanceof CrepMinefContract) { // CrepMinefContract
            $this->footer = '<table>
                        		  <tr>
                        		      <td style="width:100%" align="right">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
                        		  </tr>
                	         </table>';
        } elseif ($crep instanceof CrepAc) {
            $this->footer = '<table>
                    		  <tr>
                    		      <td style="width:100%" align="right">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
                    		  </tr>
            	         </table>';
        } elseif ($crep instanceof CrepMso3) {
            $this->footer = '<table>
                    		  <tr>
                    		      <td style="width:100%" align="right">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
                    		  </tr>
            	         </table>';
        } elseif ($crep instanceof CrepMj01) {
            $this->footer = '	<small>
    								<table>
	                    		  		<tr>
	    						  			<td style="width:20%">NOM : ' . $crep->getNomUsage() . '</td>
	    						  			<td style="width:20%">PRÉNOM : ' . $crep->getPrenom() . '</td>
	    						  			<td style="width:40%">CORPS : ' . $crep->getCorps() . '</td>
	    									<td style="width:20%" align="right">PAGE ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
	                    		  		</tr>
            	         			</table>
	    						</small>';
        } elseif ($crep instanceof CrepMj02) {
            $contentCordps = (strlen($crep->getCorps()) >= 39) ? substr($crep->getCorps(), 0, 39) . '...' : $crep->getCorps();
            $truncateCorps = ($crep->getCorps()) ? $contentCordps : null;
            $this->footer =
                '<small>
                    <table style="color: grey">
                        <tr>
                            <td style="width:10%;font-size: 9px;">CREP ' . $anneeEvaluation . '</td>
                            <td style="width:20%;font-size: 9px;">NOM : ' . $crep->getNomNaissance() . '</td>
                            <td style="width:20%;font-size: 9px;font-style: normal;">Prénom : ' . $crep->getPrenom() . '</td>
	    					<td style="width:40%;font-size: 9px;font-style: normal;">Corps : ' . $truncateCorps . '</td>
	    					<td style="width:10%;font-size: 9px;font-style: normal;" align="right">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
	                    </tr>
            	    </table>
            	 </small>';
        } elseif ($crep instanceof CrepMcc02) {
            $this->footer = '
    								<table>
	                    		  		<tr>
	    									<td align="right" style="font-size: large">' . $this->getAliasNumPage() . '</td>
	                    		  		</tr>
            	         			</table>
	    						';
        } elseif ($crep instanceof CrepEdd) {
            $this->footer = '	<small>
    								<table>
	                    		  		<tr>
	    									<td style="width:20%" align="right">PAGE ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
	                    		  		</tr>
            	         			</table>
	    						</small>';
        } elseif ($crep instanceof CrepMj02) {
            $this->footer = '
    								<table>
	                    		  		<tr>
	    									<td align="right" style="font-size: large">' . $this->getAliasNumPage() . '</td>
	                    		  		</tr>
            	         			</table>
	    						';
        } elseif ($crep instanceof CrepDgac) {
            $this->footer = '<table>
                        		  <tr>
                        		      <td style="width:100%" align="right">' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
                        		  </tr>
                	         </table>';
        } elseif ($crep instanceof CrepMso5) {
            $this->footer = '';
        } else {
            $this->footer = ' <font color="red">Footer à définir dans AppBundle\Util\AppPdf.php</font>';
        }
    }

    // Attention :
    // Les header doivent contenir exclusivement des image au format JPG.
    // Les images au format PNG font planter la génération de PDF
    public function initHeader(Crep $crep, $anneeEvaluation)
    {
        if ($crep instanceof CrepMindef01) {
            $this->header = '';

            // set default header data
        } elseif ($crep instanceof CrepMcc) {
            $this->header = '';
        } elseif ($crep instanceof CrepScl) { // CrepScl
            $logo = dirname(__FILE__) . '/../../../web/images/scl.jpg';

            $htmlHeader = '<table style="border:1px #000000 solid;padding:2px">
							<tr>
								<td width="18%" style="border:1px #000000 solid;text-align: center;" >
									<img src="' . $logo . '" alt="SCL" width="100">
								</td>
								<td align="center" width="64%" style="border:1px #000000 solid;">
									<div style="font-size:5pt">&nbsp;</div>
							  		<span style="font-size:10.0pt;">
							  			<b>COMPTE RENDU D\'ENTRETIEN PROFESSIONNEL</b>		 
							  		</span>
				  					
								</td>
								<td align="center" width="18%" style="border:1px #000000 solid;">
									<div style="font-size:5pt">&nbsp;</div>
								  	<span style="font-size:10.0pt;">
											<b>UD-SCL</b>
								  	</span>
								</td>
							</tr>
						</table>';

            $this->header = $htmlHeader;
        } elseif ($crep instanceof CrepScl02) { // CrepScl02

            $htmlHeader = '<table cellpadding="5" style="border:1px #000000 solid;padding:2px;font-size: 11.0pt;">
							<tr>
								<td align="center" width="82%" style="border:1px #000000 solid;">
									<div style="font-size:5pt">&nbsp;</div>
							  		<span style="font-size:10.0pt;">
							  			<b>COMPTE-RENDU D\'ENTRETIEN PROFESSIONNEL</b>		 
							  		</span>
								</td>
								<td align="center" width="18%" style="border:1px #000000 solid;">
									<div style="font-size:5pt">&nbsp;</div>
								  	<span style="font-size:10.0pt;">
											<b>RHU.FOR.19.V2</b>
								  	</span>
								</td>
							</tr>
						</table>';

            $this->header = $htmlHeader;
        } elseif ($crep instanceof CrepMinefAbc) {
            $this->header = '';
        } elseif ($crep instanceof CrepMinefContract) {
            $this->header = '';
        } elseif ($crep instanceof CrepAc) {
            $this->header = '';
        } elseif ($crep instanceof CrepMso3) {
            $this->header = '';
        } elseif ($crep instanceof CrepMj01) {
            $this->header = '<strong>ANNEXE 2</strong> 
        						 &nbsp;&nbsp;&nbsp;
        						 <small>APPRECIATION DE LA VALEUR PROFESSIONNELLE ' . $crep->getAgent()->getCampagnePnc()->getAnneeEvaluee() . '</small>';
        } elseif ($crep instanceof CrepMcc02) {
            $this->header = '';
        } elseif ($crep instanceof CrepEdd) {
            $this->header = '';
        } elseif ($crep instanceof CrepMj02) {
            $this->header = '';
        } elseif ($crep instanceof CrepDgac) {
            $this->header = '';
        } elseif ($crep instanceof CrepMso5) {
            $this->header = '<table>
            		  <tr align="right">
            		      <td>' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages() . '</td>
            		  </tr>
            	  </table>';
        } else {
            $this->header = ' <font color="red">Header à définir dans AppBundle\Util\AppPdf.php</font>';
        }
    }

    public function Header()
    {
        $this->writeHTMLCell(0, 0, '', '', $this->header, 0, 1, 0, true, 'top', true);
    }

    /**
     * Send the document to a given destination: string, local file or browser.
     * In the last case, the plug-in may be used (if present) or a download ("Save as" dialog box) may be forced.<br />
     * The method first calls Close() if necessary to terminate the document.
     *
     * @param $name (string) The name of the file when saved. Note that special characters are removed and blanks characters are replaced with the underscore character.
     * @param $dest (string) Destination where to send the document. It can take one of the following values:<ul><li>I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.</li><li>D: send to the browser and force a file download with the name given by name.</li><li>F: save to a local server file with the name given by name.</li><li>S: return the document as a string (name is ignored).</li><li>FI: equivalent to F + I option</li><li>FD: equivalent to F + D option</li><li>E: return the document as base64 mime multi-part email attachment (RFC 2045)</li></ul>
     *
     * @return string
     * @public
     *
     * @since 1.0
     * @see Close()
     *
     * Redéfinition de la méthode pour gérer le renommage du fichier PDF en fonction du besoin :
     * - On garde les caractère accentués
     * - On remplace les doubles espaces par un espace simple
     */
    public function Output($name = 'doc.pdf', $dest = 'I')
    {
        //Output PDF to some destination
        //Finish document if necessary
        if ($this->state < 3) {
            $this->Close();
        }
        //Normalize parameters
        if (is_bool($dest)) {
            $dest = $dest ? 'D' : 'F';
        }
        $dest = strtoupper($dest);
        if ('F' != $dest[0]) {
            $name = preg_replace('/[\s]+/', ' ', $name);
            //$name = preg_replace('/[\s]+/', '_', $name);
            //$name = preg_replace('/[^a-zA-Z0-9_\.-]/', '', $name);
        }
        if ($this->sign) {
            // *** apply digital signature to the document ***
            // get the document content
            $pdfdoc = $this->getBuffer();
            // remove last newline
            $pdfdoc = substr($pdfdoc, 0, -1);
            // remove filler space
            $byterange_string_len = strlen(TCPDF_STATIC::$byterange_string);
            // define the ByteRange
            $byte_range = array();
            $byte_range[0] = 0;
            $byte_range[1] = strpos($pdfdoc, TCPDF_STATIC::$byterange_string) + $byterange_string_len + 10;
            $byte_range[2] = $byte_range[1] + $this->signature_max_length + 2;
            $byte_range[3] = strlen($pdfdoc) - $byte_range[2];
            $pdfdoc = substr($pdfdoc, 0, $byte_range[1]) . substr($pdfdoc, $byte_range[2]);
            // replace the ByteRange
            $byterange = sprintf('/ByteRange[0 %u %u %u]', $byte_range[1], $byte_range[2], $byte_range[3]);
            $byterange .= str_repeat(' ', ($byterange_string_len - strlen($byterange)));
            $pdfdoc = str_replace(TCPDF_STATIC::$byterange_string, $byterange, $pdfdoc);
            // write the document to a temporary folder
            $tempdoc = TCPDF_STATIC::getObjFilename('doc', $this->file_id);
            $f = TCPDF_STATIC::fopenLocal($tempdoc, 'wb');
            if (!$f) {
                $this->Error('Unable to create temporary file: ' . $tempdoc);
            }
            $pdfdoc_length = strlen($pdfdoc);
            fwrite($f, $pdfdoc, $pdfdoc_length);
            fclose($f);
            // get digital signature via openssl library
            $tempsign = TCPDF_STATIC::getObjFilename('sig', $this->file_id);
            if (empty($this->signature_data['extracerts'])) {
                openssl_pkcs7_sign($tempdoc, $tempsign, $this->signature_data['signcert'], array($this->signature_data['privkey'], $this->signature_data['password']), array(), PKCS7_BINARY | PKCS7_DETACHED);
            } else {
                openssl_pkcs7_sign($tempdoc, $tempsign, $this->signature_data['signcert'], array($this->signature_data['privkey'], $this->signature_data['password']), array(), PKCS7_BINARY | PKCS7_DETACHED, $this->signature_data['extracerts']);
            }
            // read signature
            $signature = file_get_contents($tempsign);
            // extract signature
            $signature = substr($signature, $pdfdoc_length);
            $signature = substr($signature, (strpos($signature, "%%EOF\n\n------") + 13));
            $tmparr = explode("\n\n", $signature);
            $signature = $tmparr[1];
            // decode signature
            $signature = base64_decode(trim($signature));
            // add TSA timestamp to signature
            $signature = $this->applyTSA($signature);
            // convert signature to hex
            $signature = current(unpack('H*', $signature));
            $signature = str_pad($signature, $this->signature_max_length, '0');
            // Add signature to the document
            $this->buffer = substr($pdfdoc, 0, $byte_range[1]) . '<' . $signature . '>' . substr($pdfdoc, $byte_range[1]);
            $this->bufferlen = strlen($this->buffer);
        }
        switch ($dest) {
            case 'I':
                // Send PDF to the standard output
                if (ob_get_contents()) {
                    $this->Error('Some data has already been output, can\'t send PDF file');
                }
                if ('cli' != php_sapi_name()) {
                    // send output to a browser
                    header('Content-Type: application/pdf');
                    if (headers_sent()) {
                        $this->Error('Some data has already been output to browser, can\'t send PDF file');
                    }
                    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
                    //header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                    header('Pragma: public');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                    header('Content-Disposition: inline; filename="' . basename($name) . '"');
                    TCPDF_STATIC::sendOutputData($this->getBuffer(), $this->bufferlen);
                } else {
                    echo $this->getBuffer();
                }
                break;

            case 'D':
                // download PDF as file
                if (ob_get_contents()) {
                    $this->Error('Some data has already been output, can\'t send PDF file');
                }
                header('Content-Description: File Transfer');
                if (headers_sent()) {
                    $this->Error('Some data has already been output to browser, can\'t send PDF file');
                }
                header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
                //header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                header('Pragma: public');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                // force download dialog
                if (false === strpos(php_sapi_name(), 'cgi')) {
                    header('Content-Type: application/force-download');
                    header('Content-Type: application/octet-stream', false);
                    header('Content-Type: application/download', false);
                    header('Content-Type: application/pdf', false);
                } else {
                    header('Content-Type: application/pdf');
                }
                // use the Content-Disposition header to supply a recommended filename
                header('Content-Disposition: attachment; filename="' . basename($name) . '"');
                header('Content-Transfer-Encoding: binary');
                TCPDF_STATIC::sendOutputData($this->getBuffer(), $this->bufferlen);
                break;

            case 'F':
            case 'FI':
            case 'FD':
                // save PDF to a local file
                $f = TCPDF_STATIC::fopenLocal($name, 'wb');
                if (!$f) {
                    $this->Error('Unable to create output file: ' . $name);
                }
                fwrite($f, $this->getBuffer(), $this->bufferlen);
                fclose($f);
                if ('FI' == $dest) {
                    // send headers to browser
                    header('Content-Type: application/pdf');
                    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
                    //header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
                    header('Pragma: public');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                    header('Content-Disposition: inline; filename="' . basename($name) . '"');
                    TCPDF_STATIC::sendOutputData(file_get_contents($name), filesize($name));
                } elseif ('FD' == $dest) {
                    // send headers to browser
                    if (ob_get_contents()) {
                        $this->Error('Some data has already been output, can\'t send PDF file');
                    }
                    header('Content-Description: File Transfer');
                    if (headers_sent()) {
                        $this->Error('Some data has already been output to browser, can\'t send PDF file');
                    }
                    header('Cache-Control: private, must-revalidate, post-check=0, pre-check=0, max-age=1');
                    header('Pragma: public');
                    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
                    // force download dialog
                    if (false === strpos(php_sapi_name(), 'cgi')) {
                        header('Content-Type: application/force-download');
                        header('Content-Type: application/octet-stream', false);
                        header('Content-Type: application/download', false);
                        header('Content-Type: application/pdf', false);
                    } else {
                        header('Content-Type: application/pdf');
                    }
                    // use the Content-Disposition header to supply a recommended filename
                    header('Content-Disposition: attachment; filename="' . basename($name) . '"');
                    header('Content-Transfer-Encoding: binary');
                    TCPDF_STATIC::sendOutputData(file_get_contents($name), filesize($name));
                }
                break;

            case 'E':
                // return PDF as base64 mime multi-part email attachment (RFC 2045)
                $retval = 'Content-Type: application/pdf;' . "\r\n";
                $retval .= ' name="' . $name . '"' . "\r\n";
                $retval .= 'Content-Transfer-Encoding: base64' . "\r\n";
                $retval .= 'Content-Disposition: attachment;' . "\r\n";
                $retval .= ' filename="' . $name . '"' . "\r\n\r\n";
                $retval .= chunk_split(base64_encode($this->getBuffer()), 76, "\r\n");

                return $retval;

            case 'S':
                // returns PDF as a string
                return $this->getBuffer();

            default:
                $this->Error('Incorrect output destination: ' . $dest);
        }

        return '';
    }
}
