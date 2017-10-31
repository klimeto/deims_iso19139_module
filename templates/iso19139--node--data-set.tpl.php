<?php

ini_set( 'default_charset', 'UTF-8' );
// VARIABLES - might be moved to iso19139.module?

$deimsURL = $GLOBALS['base_url'];

$node = menu_get_object();

$fieldPubDate = date_format(date_create(render($content['field_publication_date'])),"Y-m-d");

$onlineLocator = field_get_items('node', $node, 'field_online_locator');

$distributionURL = $onlineLocator[0]['field_distribution_url']['und'][0]['value'];

$distributionFunction = $onlineLocator[0]['field_distribution_function']['und'][0]['value'];

$resourceLanguage = field_get_items('node', $node, 'field_language');

$accessUse = $node ->field_access_use_termref['und'];//[0]['taxonomy_term'];

$spatialScale = $node ->field_spatial_scale['und'];

$samplingTimeSpan = $node ->field_sampling_time_span['und'];

$minimumSapplingUnit = $node ->field_minimum_sampling_unit['und'];

$legalAct = field_get_items('node', $node, 'field_dataset_legal');

$relatedSite = field_get_items('node', $node, 'field_dataset_site_name_ref');

$dataSource = $node ->field_data_sources['und'];

?>

<?php echo '<?xml version="1.0" encoding="UTF-8" ?>' ?>


<gmd:MD_Metadata <?php print $namespaces; ?> >
    <gmd:fileIdentifier>
       <?php print render($content['field_uuid']); ?>
    </gmd:fileIdentifier>
   

	<gmd:language>
		<gmd:LanguageCode codeList="http://www.loc.gov/standards/iso639-2/ " codeListValue="eng">English</gmd:LanguageCode>
	</gmd:language>

   
   <gmd:characterSet>
     <gmd:MD_CharacterSetCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_CharacterSetCode" codeListValue="utf8" codeSpace="004">utf8</gmd:MD_CharacterSetCode>
   </gmd:characterSet>

   
   <gmd:hierarchyLevel>
      <gmd:MD_ScopeCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_ScopeCode" codeListValue="dataset" codeSpace="005">dataset</gmd:MD_ScopeCode>
   </gmd:hierarchyLevel>
   
   <?php print render($content['field_person_metadata_provider']); ?>

	<gmd:dateStamp>
		<gco:Date>
			<?php print date_format(date_create(render($content['field_date'])),"Y-m-d");?>
		</gco:Date>
	</gmd:dateStamp>

	
   <gmd:metadataStandardName>
      <gco:CharacterString>ISO19115</gco:CharacterString>
   </gmd:metadataStandardName>
   <gmd:metadataStandardVersion>
      <gco:CharacterString>2003/Cor.1:2006</gco:CharacterString>
   </gmd:metadataStandardVersion>
   
   <gmd:referenceSystemInfo>
      <gmd:MD_ReferenceSystem>
         <gmd:referenceSystemIdentifier>
            <gmd:RS_Identifier>
               <gmd:code>
                  <gco:CharacterString>EPSG:4326</gco:CharacterString>
               </gmd:code>
               <gmd:codeSpace>
                  <gco:CharacterString>http://www.opengis.net/def/crs/EPSG/0/4326</gco:CharacterString>
               </gmd:codeSpace>
            </gmd:RS_Identifier>
         </gmd:referenceSystemIdentifier>
      </gmd:MD_ReferenceSystem>
  </gmd:referenceSystemInfo>
  
  <gmd:referenceSystemInfo>
      <gmd:MD_ReferenceSystem>
         <gmd:referenceSystemIdentifier>
            <gmd:RS_Identifier>
               <gmd:code>
                  <gco:CharacterString>http://www.opengis.net/def/crs/EPSG/0/4258</gco:CharacterString>
               </gmd:code>
            </gmd:RS_Identifier>
         </gmd:referenceSystemIdentifier>
      </gmd:MD_ReferenceSystem>
  </gmd:referenceSystemInfo>
   

   <gmd:identificationInfo>
      <gmd:MD_DataIdentification>
         <gmd:citation>
            <gmd:CI_Citation>
               <gmd:title><gco:CharacterString><?php /*print utf8_decode($label)*/ print ($label); ?></gco:CharacterString></gmd:title>
               <gmd:date>
                 <gmd:CI_Date>
                   <gmd:date>
						<gco:Date><?php if ($fieldPubDate != $pubDate){
											print $fieldPubDate;
										}
										else {
											print $pubDate;
										}
									?>
						</gco:Date>
					</gmd:date>
                   <gmd:dateType>
                     <gmd:CI_DateTypeCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#CI_DateTypeCode" codeListValue="publication" codeSpace="002">publication</gmd:CI_DateTypeCode>
                   </gmd:dateType>
                 </gmd:CI_Date>
               </gmd:date>
			   <gmd:identifier>
			   <?php if (empty($content['field_dataset_site_name_ref'])): ?>
					<gmd:MD_Identifier>
					  <gmd:code>
						<gco:CharacterString>urn:ltereurope:inspire:<?php print ((render ($content['field_data_set_id'])) . ":". $node->nid . ":" . $node->vid) ?></gco:CharacterString>
					  </gmd:code>
					</gmd:MD_Identifier>
				<?php endif; ?>
				<?php if (!empty($content['field_dataset_site_name_ref'])): ?>
					<gmd:RS_Identifier>
					  <gmd:code>
						<gco:CharacterString>urn:ltereurope:inspire:<?php print ((render ($content['field_data_set_id'])) . ":". $node->nid . ":" . $node->vid) ?></gco:CharacterString>
					  </gmd:code>
					</gmd:MD_Identifier>
					  <gmd:codeSpace>
						<gco:CharacterString><?php print render($content['field_dataset_site_name_ref']) ?></gco:CharacterString>
						</gmd:codeSpace>
					</gmd:RS_Identifier>
			   </gmd:identifier>
				<?php endif; ?>
				
               <?php print render($content['field_person_creator']); ?>
			   
			   
               <gmd:presentationForm>
                  <gmd:CI_PresentationFormCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#CI_PresentationFormCode" codeListValue="documentDigital" codeSpace="001">documentDigital</gmd:CI_PresentationFormCode>
               </gmd:presentationForm>
            </gmd:CI_Citation>
         </gmd:citation>
         
		 
		 <?php if (!empty($content['field_abstract'])): ?>
          <gmd:abstract>
            <?php print render($content['field_abstract']); ?>
          </gmd:abstract>
         <?php endif; ?>
         
		 
		 <?php if (!empty($content['field_purpose'])): ?>
          <gmd:purpose>
            <?php print render($content['field_purpose']); ?>
          </gmd:purpose>
         <?php endif; ?>
         
		 
		 <?php if (!empty($content['field_project_roles'])): ?>
           <gmd:credit>
             <?php print render($content['field_project_roles']); ?>
           </gmd:credit>
         <?php endif; ?>
         
		 
		 <gmd:status>
           <gmd:MD_ProgressCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_ProgressCode" codeListValue="completed" codeSpace="001">completed</gmd:MD_ProgressCode>
         </gmd:status>    
         
         
		 <?php print render($content['field_person_contact']); ?>

         
		 <?php if (!empty($content['field_spatial_scale']) || !empty($content['field_sampling_time_span']) || !empty($content['field_minimum_sampling_unit'])): ?>
          <gmd:resourceMaintenance>
             <gmd:MD_MaintenanceInformation>
                <gmd:maintenanceAndUpdateFrequency>
                   <gmd:MD_MaintenanceFrequencyCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_MaintenanceFrequencyCode" codeListValue="continual" >continual</gmd:MD_MaintenanceFrequencyCode>
                </gmd:maintenanceAndUpdateFrequency>
                <gmd:maintenanceNote>
                   <?php 
						if (!empty($content['field_spatial_scale'])){
						$spatialScaleText = ('Representative area of sampling: '. $spatialScale[0]['taxonomy_term'] -> name);
						}
						if (!empty($content['field_sampling_time_span'])){
						$samplingTimeSpanText = ('Sampling frequency - sampling time span: '. $samplingTimeSpan[0]['taxonomy_term'] -> name);
						}
						if (!empty($content['field_minimum_sampling_unit'])){
						$minimumSapplingUnitText = ('Sampling frequency - minimmum sampling unit: '. $minimumSapplingUnit[0]['taxonomy_term'] -> name);
						}						
					?>
					<gco:CharacterString><?php print $spatialScaleText. ' '.$samplingTimeSpanText. ' '.$minimumSapplingUnitText ?></gco:CharacterString>
                </gmd:maintenanceNote>
             </gmd:MD_MaintenanceInformation>
          </gmd:resourceMaintenance>
         <?php endif; ?>
		
		
		<gmd:descriptiveKeywords>
			<gmd:MD_Keywords>
					<gmd:keyword>
						<gco:CharacterString>Environmental monitoring facilities</gco:CharacterString>
					</gmd:keyword>
					<gmd:thesaurusName>
						<gmd:CI_Citation>
							<gmd:title>
								<gco:CharacterString>GEMET - INSPIRE themes, version 1.0</gco:CharacterString>
							</gmd:title>
							<gmd:date>
								<gmd:CI_Date>
									<gmd:date>
										<gco:Date>2008-06-01</gco:Date>
									</gmd:date>
									<gmd:dateType>
										<gmd:CI_DateTypeCode codeList="http://standards.iso.org/ittf/PubliclyAvailableStandards/ISO_19139_Schemas/resources/Codelist/ML_gmxCodelists.xml#CI_DateTypeCode" codeListValue="publication">publication</gmd:CI_DateTypeCode>
									</gmd:dateType>
								</gmd:CI_Date>
							</gmd:date>
						</gmd:CI_Citation>
					</gmd:thesaurusName>
			</gmd:MD_Keywords>
		</gmd:descriptiveKeywords>
		
		 
		 <?php if (!empty($content['field_free_keywords_ref'])): ?>
		 <gmd:descriptiveKeywords>
			<gmd:MD_Keywords>
			<?php print render($content['field_free_keywords_ref']); ?>
			</gmd:MD_Keywords>
		</gmd:descriptiveKeywords>
		<?php endif; ?>
 		 
         
		 <?php print render($content['keywordSets']); ?>
  
         
		 <?php if (!empty($data_policies)): ?>
           <gmd:resourceConstraints>
             <gmd:MD_LegalConstraints>
               <gmd:accessConstraints>
                  <gmd:MD_RestrictionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_RestrictionCode" codeListValue="otherRestrictions" codeSpace="008">otherRestrictions</gmd:MD_RestrictionCode>
               </gmd:accessConstraints>
               <gmd:useConstraints>
                 <gmd:MD_RestrictionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_RestrictionCode" codeListValue="otherRestrictions" codeSpace="008">otherRestrictions</gmd:MD_RestrictionCode>
               </gmd:useConstraints>
               <gmd:otherConstraints>
                  <gco:CharacterString>
                    <?php print $data_policies; ?>
                  </gco:CharacterString>
	       </gmd:otherConstraints>
	     </gmd:MD_LegalConstraints>
           </gmd:resourceConstraints>
         <?php endif; ?>
		 
		 
		 <gmd:resourceConstraints>
            <gmd:MD_LegalConstraints>
			<?php print render($content['field_dataset_rights']);?>
               
               <?php if (!empty ($content['field_access_use_termref'])): ?>
			   <gmd:accessConstraints>
                  <gmd:MD_RestrictionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml"
                                          codeListValue="otherRestrictions"
                                          codeSpace="ISOTC211/19115"/>
               </gmd:accessConstraints>
			   <?php foreach ($accessUse as $item): ?>
			   <gmd:otherConstraints>
                  <gco:CharacterString>
				  
				  <?php $term = ($item['taxonomy_term'] -> name);
						$parent = taxonomy_get_parents(($item['taxonomy_term'] -> tid));
						$parentName = reset ($parent) -> name;
						$accessUseText = ('The principal: '.$parentName.' has granted the access and use permission: '.$term);
						print $accessUseText;
				  ?>
				  </gco:CharacterString>
               </gmd:otherConstraints>
			   <?php endforeach;?>
			   <?php endif; ?>
            </gmd:MD_LegalConstraints>
         </gmd:resourceConstraints>
		 
		 
		 <?php foreach($resourceLanguage as $item): ?>
		 <gmd:language>
			<gmd:LanguageCode codeList="http://www.loc.gov/standards/iso639-2/ " codeListValue="<?php print $item['value']; ?>"><?php print $item['value']; ?></gmd:LanguageCode>
		 </gmd:language>
		 <?php endforeach; ?>
		 
		 
		 <gmd:topicCategory>
            <gmd:MD_TopicCategoryCode>environment</gmd:MD_TopicCategoryCode>
         </gmd:topicCategory>
		 
		 
         <gmd:extent>
            <gmd:EX_Extent>
               <?php print render($content['field_related_sites']); ?>
            </gmd:EX_Extent>
         </gmd:extent>
		 
		 
		 <?php if(!empty($content['field_spatial_scale'])): ?>
		 <gmd:extent>
			<gmd:EX_Extent>
			  <gmd:geographicElement>
				<gmd:EX_GeographicDescription>
				  <gmd:geographicIdentifier>
					<gmd:MD_Identifier>
					  <gmd:code>
						<gco:CharacterString><?php print ('Representative area of sampling: '. $spatialScale[0]['taxonomy_term'] -> name); ?></gco:CharacterString>
					  </gmd:code>
					</gmd:MD_Identifier>
				  </gmd:geographicIdentifier>
				</gmd:EX_GeographicDescription>
			  </gmd:geographicElement>
			</gmd:EX_Extent>
		</gmd:extent>
		<?php endif;?>
		 
		 
		 
		 <gmd:extent>
            <gmd:EX_Extent>
               <?php print render($content['field_date_range']); ?>
            </gmd:EX_Extent>
         </gmd:extent>

         <?php if (!empty($content['field_additional_information'])): ?>
          <gmd:supplementalInformation>
            <?php print render($content['field_additional_information']); ?>
          </gmd:supplementalInformation>
         <?php endif; ?>
      </gmd:MD_DataIdentification>
   </gmd:identificationInfo>

   
	<?php if (!empty($content['field_online_locator']) || !empty($content['field_data_sources'])): ?>
	<gmd:distributionInfo>
		<gmd:MD_Distribution>
		<?php print render($content['field_data_sources']); ?>
		<?php if (!empty($content['field_online_locator'])): ?>
			<?php foreach ($onlineLocator as $item): ?>
				<gmd:transferOptions>
					<gmd:MD_DigitalTransferOptions>
						<gmd:onLine>
							<gmd:CI_OnlineResource>
								<gmd:linkage>
									<gmd:URL><?php print $item[field_distribution_url][und][0][value] ?></gmd:URL>
								</gmd:linkage>
								<?php if (!empty($item[field_distribution_function][und][0][value])): ?>
								<gmd:protocol>
									<gco:CharacterString><?php switch ($item[field_distribution_function][und][0][value]) {
										case "arcims_mscf":
											echo "ESRI:AIMS--http--configuration";
											break;
										case "arcims_ifms":
											echo "ESRI:AIMS--http-get-feature";
											break;
										case "arcims_ims":
											echo "ESRI:AIMS--http-get-image";
											break;
										case "google_kml":
											echo "GLG:KML-2.0-http-get-map";
											break;
										case "ogc_csw":
											echo "OGC:CSW";
											break;
										case "ogc_kml":
											echo "OGC:KML";
											break;
										case "ogc_gml|":
											echo "OGC:GML";
											break;
										case "ogc_ods":
											echo "OGC:ODS";
											break;
										case "ogc_ods_ogs":
											echo "OGC:OGS";
											break;
										case "ogc_ods_ous":
											echo "OGC:OUS";
											break;
										case "ogc_ods_ops":
											echo "OGC:OPS";
											break;
										case "ogc_ods_ors":
											echo "OGC:ORS";
											break;
										case "ogc_sos":
											echo "OGC:SOS";
											break;
										case "ogc_sps":
											echo "OGC:SPS";
											break;
										case "ogc_sas":
											echo "OGC:SAS";
											break;
										case "ogc_wcs":
											echo "OGC:WCS";
											break;
										case "ogc_wcs_1_1_0":
											echo "OGC:WCS-1.1.0-http-get-capabilities";
											break;
										case "ogc_wcts":
											echo "OGC:WCTS";
											break;
										case "ogc_wfs":
											echo "OGC:WFS";
											break;
										case "ogc_wfs_g":
											echo "OGC:WFS-G";
											break;
										case "ogc_wmc":
											echo "OGC:WMC";
											break;
										case "ogc_wms":
											echo "OGC:WMS";
											break;
										case "ogc_wms_cap_1_1_1":
											echo "OGC:WMS-1.1.1-http-get-capabilities";
											break;
										case "ogc_wms_cap_1_3_0":
											echo "OGC:WMS-1.3.0-http-get-capabilities";
											break;
										case "ogc_wms_1_1_1":
											echo "OGC:WMS-1.1.1-http-get-map";
											break;
										case "ogc_wms_1_3_0":
											echo "OGC:WMS-1.3.0-http-get-map";
											break;
										case "ogc_sos_get_1_0_0":
											echo "OGC:SOS-1.0.0-http-get-observation";
											break;
										case "ogc_sos_get_post_1_0_0":
											echo "OGC:SOS-1.0.0-http-post-observation";
											break;
										case "ogc_wns":
											echo "OGC:WNS";
											break;
										case "ogc_wps":
											echo "OGC:WPS";
											break;
										case "ogc_ows":
											echo "OGC:OWS-C";
											break;
										case "file_ftp":
											echo "WWW:DOWNLOAD-1.0-ftp--download";
											break;
										case "file_download":
											echo "WWW:DOWNLOAD-1.0-http--download";
											break;
										case "file_gis":
											echo "FILE:GEO";
											break;
										case "file_raster":
											echo "FILE:RASTER";
											break;
										case "rss":
											echo "WWW:LINK-1.0-http--rss";
											break;
										case "postgis":
											echo "DB:POSTGIS";
											break;
										case "oracle":
											echo "DB:ORACLE";
											break;
										case "opendap":
											echo "WWW:LINK-1.0-http--opendap";
											break;
										case "dataturbine":
											echo "RBNB:DATATURBINE";
											break;
										case "url":
											echo "WWW:LINK-1.0-http--link";
											break;
										case "email":
											echo "UKST";
											break;
										case "unknown":
											echo "UKST";
											break;				
									}?></gco:CharacterString>
								</gmd:protocol>
								<?php endif; ?>
								
								<?php if (!empty($item[field_distribution_url][und][0][title])): ?>
									<gmd:name><gco:CharacterString><?php print $item[field_distribution_url][und][0][title] ?></gco:CharacterString></gmd:name>
								<?php endif; ?>
								
								<?php if (!empty($item[field_distribution_function][und][0][value])): ?>
								<gmd:function>
									<gmd:CI_OnLineFunctionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#CI_OnLineFunctionCode" codeListValue=" <?php  
										switch ($item[field_distribution_function][und][0][value]) {
											case "arcims_mscf":
												echo "information";
												break;
											case "arcims_ifms":
												echo "download";
												break;
											case "arcims_ims":
												echo "browseGraphic";
												break;
											case "google_kml":
												echo "download";
												break;
											case "ogc_csw":
												echo "search";
												break;
											case "ogc_kml":
												echo "download";
												break;
											case "ogc_gml|":
												echo "download";
												break;
											case "ogc_ods":
												echo "search";
												break;
											case "ogc_ods_ogs":
												echo "information";
												break;
											case "ogc_ods_ous":
												echo "information";
												break;
											case "ogc_ods_ops":
												echo "information";
												break;
											case "ogc_ods_ors":
												echo "information";
												break;
											case "ogc_sos":
												echo "download";
												break;
											case "ogc_sps":
												echo "information";
												break;
											case "ogc_sas":
												echo "download";
												break;
											case "ogc_wcs":
												echo "download";
												break;
											case "ogc_wcs_1_1_0":
												echo "download";
												break;
											case "ogc_wcts":
												echo "information";
												break;
											case "ogc_wfs":
												echo "download";
												break;
											case "ogc_wfs_g":
												echo "download";
												break;
											case "ogc_wmc":
												echo "information";
												break;
											case "ogc_wms":
												echo "information";
												break;
											case "ogc_wms_cap_1_1_1":
												echo "completeMetadata";
												break;
											case "ogc_wms_cap_1_3_0":
												echo "completeMetadata";
												break;
											case "ogc_wms_1_1_1":
												echo "completeMetadata";
												break;
											case "ogc_wms_1_3_0":
												echo "completeMetadata";
												break;
											case "ogc_sos_get_1_0_0":
												echo "download";
												break;
											case "ogc_sos_get_post_1_0_0":
												echo "download";
												break;
											case "ogc_wns":
												echo "information";
												break;
											case "ogc_wps":
												echo "information";
												break;
											case "ogc_ows":
												echo "information";
												break;
											case "file_ftp":
												echo "download";
												break;
											case "file_download":
												echo "download";
												break;
											case "file_gis":
												echo "download";
												break;
											case "file_raster":
												echo "download";
												break;
											case "rss":
												echo "information";
												break;
											case "postgis":
												echo "download";
												break;
											case "oracle":
												echo "download";
												break;
											case "opendap":
												echo "information";
												break;
											case "dataturbine":
												echo "download";
												break;
											case "email":
												echo "emailService";
												break;
											case "url":
												echo "information";
												break;
											case "unknown":
												echo "_unknown";
												break;				
										}?>"/>
								</gmd:function>
								<?php endif;?>
							</gmd:CI_OnlineResource>
						</gmd:onLine>
					</gmd:MD_DigitalTransferOptions>
				</gmd:transferOptions>
		<?php endforeach; ?>
		<?php endif; ?>
		</gmd:MD_Distribution>
	</gmd:distributionInfo>
	<?php endif; ?>
    <gmd:dataQualityInfo>
      <gmd:DQ_DataQuality>
        <gmd:scope>
          <gmd:DQ_Scope>
            <gmd:level>
              <gmd:MD_ScopeCode codeList="http://www.ngdc.noaa.gov/metadata/published/xsd/schema/resources/Codelist/gmxCodelists.xml#MD_ScopeCode" codeListValue="dataset">dataset</gmd:MD_ScopeCode>
            </gmd:level>
          </gmd:DQ_Scope>
        </gmd:scope>
		<gmd:report>
            <gmd:DQ_DomainConsistency>
               <gmd:measureIdentification>
                  <gmd:RS_Identifier>
                     <gmd:code>
                        <gco:CharacterString>Conformity_001</gco:CharacterString>
                     </gmd:code>
                     <gmd:codeSpace>
                        <gco:CharacterString>INSPIRE</gco:CharacterString>
                     </gmd:codeSpace>
                  </gmd:RS_Identifier>
               </gmd:measureIdentification>
               <gmd:result>
                  <gmd:DQ_ConformanceResult>
                     <gmd:specification>
                        <gmd:CI_Citation>
                           <gmd:title>
                              <gco:CharacterString>COMMISSION REGULATION (EU) No 1089/2010 of 23 November 2010 implementing Directive 2007/2/EC of the European Parliament and of the Council as regards interoperability of spatial data sets and services</gco:CharacterString>
                           </gmd:title>
                           <gmd:date>
                              <gmd:CI_Date>
                                 <gmd:date>
                                    <gco:Date>2010-12-08</gco:Date>
                                 </gmd:date>
                                 <gmd:dateType>
                                    <gmd:CI_DateTypeCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml"
                                                         codeListValue="publication"
                                                         codeSpace="ISOTC211/19115">publication</gmd:CI_DateTypeCode>
                                 </gmd:dateType>
                              </gmd:CI_Date>
                           </gmd:date>
                        </gmd:CI_Citation>
                     </gmd:specification>
                     <gmd:explanation>
                        <gco:CharacterString>See the referenced specification</gco:CharacterString>
                     </gmd:explanation>
                     <gmd:pass gco:nilReason="unknown"/>
                  </gmd:DQ_ConformanceResult>
               </gmd:result>
            </gmd:DQ_DomainConsistency>
         </gmd:report>
		 
		 <?php if(!empty($content['field_dataset_legal'])):?>
		 <?php foreach ($legalAct as $item): ?>
		 <gmd:report>
            <gmd:DQ_DomainConsistency>
               <gmd:result>
                  <gmd:DQ_ConformanceResult>
                     <gmd:specification>
                        <gmd:CI_Citation>
                           <gmd:title>
                              <gco:CharacterString>
								<?php
									$legalActTextFull = $item['value'];
									$legalActArray = explode(';', $legalActTextFull);
									print $legalActArray[0];
								?>
							   </gco:CharacterString>
                           </gmd:title>
                           <gmd:date>
                              <gmd:CI_Date>
                                 <gmd:date>
                                    <gco:Date><?php print $legalActArray[1];?></gco:Date>
                                 </gmd:date>
                                 <gmd:dateType>
                                    <gmd:CI_DateTypeCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml"
                                                         codeListValue="<?php print $legalActArray[2];?>"
                                                         codeSpace="ISOTC211/19115"><?php print $legalActArray[2];?></gmd:CI_DateTypeCode>
                                 </gmd:dateType>
                              </gmd:CI_Date>
                           </gmd:date>
                        </gmd:CI_Citation>
                     </gmd:specification>
                     <gmd:explanation>
                        <gco:CharacterString>See the referenced specification</gco:CharacterString>
                     </gmd:explanation>
                     <gmd:pass gco:nilReason="unknown"/>
                  </gmd:DQ_ConformanceResult>
               </gmd:result>
            </gmd:DQ_DomainConsistency>
         </gmd:report>
		 <?php endforeach; ?>	 
		 <?php endif; ?>
		
        <gmd:lineage>
			<gmd:LI_Lineage>
               <gmd:statement>
					<gco:CharacterString><?php print 'Method description: ' . render($content['field_methods']) . '. Method URL: ' . render($content['field_related_links']) . '. Instrumentation: '.render($content['field_instrumentation']); ?></gco:CharacterString>
			   </gmd:statement>
			</gmd:LI_Lineage>
        </gmd:lineage>
      </gmd:DQ_DataQuality>
    </gmd:dataQualityInfo>

   <gmd:metadataConstraints>
     <gmd:MD_LegalConstraints>
       <gmd:accessConstraints>
         <gmd:MD_RestrictionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_RestrictionCode" codeListValue="otherRestrictions" codeSpace="008"/>
       </gmd:accessConstraints>
       <gmd:useConstraints>
         <gmd:MD_RestrictionCode codeList="http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_RestrictionCode" codeListValue="otherRestrictions" codeSpace="008"/>
       </gmd:useConstraints>
       <gmd:otherConstraints>
         <gco:CharacterString>Metadata Access Constraints: none Metadata Use Constraints: none</gco:CharacterString>
       </gmd:otherConstraints>
     </gmd:MD_LegalConstraints>
   </gmd:metadataConstraints>

   <gmd:metadataMaintenance>
     <gmd:MD_MaintenanceInformation>
       <gmd:maintenanceAndUpdateFrequency>
         <gmd:MD_MaintenanceFrequencyCode codeList="http://www.ngdc.noaa.gov/metadata/published/xsd/schema/resources/Codelist/gmxCodelists.xml#MD_MaintenanceFrequencyCode" codeListValue="annually">annually</gmd:MD_MaintenanceFrequencyCode>
       </gmd:maintenanceAndUpdateFrequency>
     </gmd:MD_MaintenanceInformation>
   </gmd:metadataMaintenance>
</gmd:MD_Metadata>
