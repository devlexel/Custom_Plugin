<?php  
 function fetch_data()  
 {  
      $output = '';  
     
      for($i = 1; $i<=20; $i++){ 
      $output .= '<tr>
<td width="184"; > Name of camper comes here </td>
<td width="40"></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
</tr> 
                          ';  
      }  
      return $output;  
 }  

      require_once('tcpdf_include.php');
      $obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
      $obj_pdf->SetCreator(PDF_CREATOR);  
      // $obj_pdf->SetTitle("Generate HTML Table Data To PDF From MySQL Database Using TCPDF In PHP");  
      // $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
      // $obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
      // $obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
      // $obj_pdf->SetDefaultMonospacedFont('helvetica');  
      // $obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
      // $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
      $obj_pdf->setPrintHeader(false);  
      $obj_pdf->setPrintFooter(true);  
      $obj_pdf->SetAutoPageBreak(TRUE, 10);  
      // $obj_pdf->SetFont('helvetica', '', 11);  
      $obj_pdf->AddPage('L');  


 


      $content = '';  
      $content .= '  
<table border="0">
<tr>
<td width="230">x</td>
<td width="530">
Location:<br/>
Lead Scientists:<br/>
Session Dates:
<p style="font-size:10px;">If absent, denote with "x". If photo permissions different than registration, guardian must initial box after designating
the field. Guardians must initial Sign In/Out each day for chain of custody</p>
</td>
</tr>
<br/>
<table border="1">
<tr>
<th colspan="4"></th>
<th colspan="2" align="center">Mon</th>
<th colspan="2" align="center">Tue</th>
<th colspan="2" align="center">Wed</th>
<th colspan="2" align="center">Thu</th>
<th colspan="2" align="center">Fri</th>
</tr>
<tr>
<th width="184"> Camper </th>
<th width="40"> Photo </th>
<th align="center"> SignIn </th>
<th align="center"> SignOut </th>
<th align="center"> SignIn </th>
<th align="center"> SignOut </th>
<th align="center"> SignIn </th>
<th align="center"> SignOut </th>
<th align="center"> SignIn </th>
<th align="center"> SignOut </th>
<th align="center"> SignIn </th>
<th align="center"> SignOut </th>
</tr>
      ';  
      $content .= fetch_data();  
      $content .= '</table>';  
      $obj_pdf->writeHTML($content);  
      ob_end_clean();
      $obj_pdf->Output('file.pdf', 'I');  
 
 ?>  
