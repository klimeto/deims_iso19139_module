<?php
/**
*		DATA SOURCE TEMPLATE
**/

$deimsURL = $GLOBALS['base_url'];

?>

<?php if (!empty($content['field_data_source_file'])): ?>

		   <?php 
			$fileURL = pathinfo(render($content['field_data_source_file']));
			$temp_source_url = render($content['field_data_source_file']);
			
			// drupal formatter isn't printing the pure url to the export, therefore this bit is needed to extract the url value
			if (strpos($temp_source_url, '<a href="') !== false) {
				
				$start_pos = strpos($temp_source_url, '<a href="') + 9;
				$correct_start = substr($temp_source_url, $start_pos);
				
				$end_pos = strpos($correct_start, '" ');
				$corr_url = mb_substr($correct_start, 0, $end_pos);
				
			}
			// this section can be removed if the drupal bug is fixed
			
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
    
		  <gmd:transferOptions>
			  <gmd:MD_DigitalTransferOptions>
				 <gmd:onLine>
					<gmd:CI_OnlineResource>
					   <gmd:linkage>
						  <gmd:URL><?php echo ($corr_url); ?></gmd:URL>
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