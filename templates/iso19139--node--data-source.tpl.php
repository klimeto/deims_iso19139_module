<?php
/**
*		DATA SOURCE TEMPLATE
**/

$deimsURL = $GLOBALS['base_url'];

?>

<?php if (!empty($content['field_data_source_file'])): ?>

      <!--
	  <gmd:distributionFormat>
         <gmd:MD_Format>
           <gmd:name>
		   <?php 
			$fileURL = pathinfo(render($content['field_data_source_file']));

			$extension = '';
			$filename = '';
			if (isset($fileURL['extension'])) {
				$extension = $fileURL['extension'];
			}
			
			if (isset($fileURL['extension'])) {
				$fileName = $fileURL['filename'];
			}

			if ($extension == 'xlsx'){
				$excelType = 'Microsoft Excel Open XML Document';
				$excelVersion = 'Excel 2007 and later';
			}
			elseif ($extension == 'xls'){
				$excelType = 'Worksheet file (Microsoft Excel)';
				$excelVersion = 'Excel 97-2003';
			}
			else {
				$excelType = $extension;
				$excelVersion = "N/A";
			}
		   ?>
             <gco:CharacterString><?php print($excelType); ?></gco:CharacterString>
           </gmd:name>
           <gmd:version><gco:CharacterString><?php print($excelVersion) ?></gco:CharacterString></gmd:version>
         </gmd:MD_Format>
      </gmd:distributionFormat>
	  -->
		  <gmd:transferOptions>
			  <gmd:MD_DigitalTransferOptions>
				 <gmd:onLine>
					<gmd:CI_OnlineResource>
					   <gmd:linkage>
						  <gmd:URL><?php print render($content['field_data_source_file']); ?></gmd:URL>
					   </gmd:linkage>
					   <gmd:name>
						<gco:CharacterString><?php print $entity->title ?></gco:CharacterString>
					   </gmd:name>
					   <?php if (!empty($content['field_description'])): ?>
					   <gmd:description><?php print render($content['field_description']); ?></gmd:description>
					   <?php endif; ?>
					   <gmd:function>
						<gmd:CI_OnLineFunctionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#CI_OnLineFunctionCode" codeListValue="download"/>
					   </gmd:function>
					</gmd:CI_OnlineResource>
				 </gmd:onLine>
			   </gmd:MD_DigitalTransferOptions>
		  </gmd:transferOptions>
<?php else: ?>
		<gmd:transferOptions>
				  <gmd:MD_DigitalTransferOptions>
					 <gmd:onLine>
						<gmd:CI_OnlineResource>
						   <gmd:linkage>
							  <gmd:URL><?php print ($deimsURL ."/node/" .$entity->nid) ?></gmd:URL>
						   </gmd:linkage>
						   <gmd:name>
							<gco:CharacterString><?php print $entity->title ?></gco:CharacterString>
						   </gmd:name>
						   <?php if (!empty($content['field_description'])): ?>
						   <gmd:description><?php print render($content['field_description']); ?></gmd:description>
						   <?php endif; ?>
						   <gmd:function>
							<gmd:CI_OnLineFunctionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#CI_OnLineFunctionCode" codeListValue="download"/>
						   </gmd:function>
						</gmd:CI_OnlineResource>
					 </gmd:onLine>
				   </gmd:MD_DigitalTransferOptions>
			  </gmd:transferOptions>




<?php endif; ?>
