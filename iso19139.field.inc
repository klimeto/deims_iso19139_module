<?php

/**
 * @file
 * Field integration for the ISO 19139 module.
 */

/**
 * Implements hook_field_formatter_info().
 */
function iso19139_field_formatter_info() {
  $info['taxonomy_iso19139_keywordset'] = array(
    'label' => t('ISO 19139 keywordSet'),
    'field types' => array('taxonomy_term_reference'),
    'settings' => array(
      'keyword_value' => '[term:name]',
      'thesaurus_value' => '[term:vocabulary]',
    ),
  );
  $info['entityreference_iso19139_element'] = array(
    'label' => t('ISO 19139 element'),
    'field types' => array('entityreference'),
    'settings' => array(
      'element' => '',
      'multiple' => 0,
    ),
  );
  $info['name_iso19139_individualName'] = array(
    'label' => t('ISO 19139 individualName'),
    'field types' => array('name'),
  );
  
  
  $info['addressfield_iso19139_address'] = array(
    'label' => t('ISO 19139 address'),
    'field types' => array('addressfield', 'email'),
  );
/**
* Since the email in ISO needs to be lumped with the
* physical address, make the email part of the address
* field formatter, commenting this out.
*
*  $info['iso19139_electronicMailAddress'] = array(
*    'label' => t('ISO 19139 electronicMailAddress'),
*    'field types' => array('email'),
*  );
*/
  $info['telephone_iso19139_phone'] = array(
    'label' => t('ISO 19139 phone'),
    'field types' => array('telephone'),
    'settings' => array(
      'phonetype' => 'voice',
    ),
  );
  $info['date_iso19139_temporalCoverage'] = array(
    'label' => t('ISO 19139 temporalCoverage'),
    'field types' => array('date', 'datestamp', 'datetime'),
  );
  $info['geofield_iso19139_boundingCoordinates'] = array(
    'label' => t('ISO 19139 boundingCoordinates'),
    'field types' => array('geofield'),
  );
  $info['iso19139_onlineUrl'] = array(
    'label' => t('ISO 19139 onlineUrl'),
    'field types' => array('link_field', 'url'),
  );
  $info['iso19139_text'] = array(
    'label' => t('ISO 19139 text'),
    'field types' => array('text', 'text_long', 'text_with_summary'),
  );
  
  $info['iso19139_inspire_data_theme'] = array(
    'label' => t('iso19139 inspire data theme'),
    'field types' => array('taxonomy_term_reference'),
    'settings' => array(
      'keyword_value' => '[term:name]',
      'thesaurus_value' => '[term:vocabulary]',
    ),
  );
  
    // KLIMETO
  $info['iso19139_text_ipr'] = array(
    'label' => t('ISO 19139 text ipr'),
    'field types' => array('text', 'text_long', 'text_with_summary'),
  );

  // Add a low weight to each formatter so that it isn't assigned as the
  // defaul formatter for new fields.
  foreach ($info as &$formatter) {
    $formatter['weight'] = 120;
  }

  return $info;
}

/**
 * Implements hook_field_formatter_settings_summary().
 */
function iso19139_field_formatter_settings_summary($field, $instance, $view_mode) {
  $display = $instance['display'][$view_mode];
  $settings = $display['settings'];

  $summary = array();

  switch ($display['type']) {
    case 'taxonomy_iso19139_keywordset':
      $summary[] = t('Keyword value: @value', array('@value' => $settings['keyword_value']));
      $summary[] = t('Thesaurus value: @value', array('@value' => $settings['thesaurus_value']));
      break;

    case 'entityreference_iso19139_element':
      $summary[] = t('Element name: @value', array('@value' => !empty($settings['element']) ? $settings['element'] : t('None')));
      $summary[] = empty($settings['multiple']) ? t('Output multiple values inside one tag.') : t('Output multiple values as individual tags.');
      break;

    case 'telephone_iso19139_phone':
      $summary[] = t('Type: @value', array('@value' => $settings['phonetype']));
      break;
  }

  return implode('<br />', $summary);
}

/**
 * Implements hook_field_formatter_settings_form().
 */
function iso19139_field_formatter_settings_form($field, $instance, $view_mode, $form, &$form_state) {
  $display = $instance['display'][$view_mode];
  $settings = $display['settings'];

  $element = array();

  switch ($display['type']) {
    case 'taxonomy_iso19139_keywordset':
      $element['keyword_value'] = array(
        '#title' => t('Keyword element value'),
        '#type' => 'textfield',
        '#default_value' => $settings['keyword_value'],
        '#element_validate' => array('token_element_validate'),
        '#token_types' => array('term'),
        '#required' => TRUE,
      );
      $element['thesaurus_value'] = array(
        '#title' => t('Thesaurus element value'),
        '#type' => 'textfield',
        '#default_value' => $settings['thesaurus_value'],
        '#element_validate' => array('token_element_validate'),
        '#token_types' => array('term'),
        '#required' => TRUE,
      );
      break;

    case 'entityreference_iso19139_element':
      $element['element'] = array(
        '#title' => t('ISO_19139 element name'),
        '#type' => 'textfield',
        '#default_value' => $settings['element'],
        '#description' => t('If blank, will not output a root XML element and only output the contents from the template.'),
      );
      $element['multiple'] = array(
        '#type' => 'checkbox',
        '#title' => t('Output multiple values as individual tags.'),
        '#default_value' => $settings['multiple'],
      );
      break;

    case 'telephone_iso19139_phone':
      $element['phonetype'] = array(
        '#title' => t('Type'),
        '#type' => module_exists('select_or_other') ? 'select_or_other' : 'select',
        '#options' => drupal_map_assoc(array('voice', 'facsimile', 'tdd')),
        '#default_value' => $settings['phonetype'],
      );
      break;
  }
  return $element;
}

/**
 * Implements hook_field_formatter_view().
 */
function iso19139_field_formatter_view($entity_type, $entity, $field, $instance, $langcode, $items, $display) {
  $element = array();
  $settings = $display['settings'];

  switch ($display['type']) {
    case 'taxonomy_iso19139_keywordset':
      $tids = array();
      foreach ($items as $item) {
        $tids[] = $item['tid'];
      }
      $terms = taxonomy_term_load_multiple($tids);
      $keywords = array();
      foreach ($items as $delta => $item) {
        $keywords[] = array(
          'key' => 'gmd:keyword',
          'value' => array('gco:CharacterString' => token_replace($settings['keyword_value'], array('term' => $terms[$item['tid']]))),
        );
      }
      if (!empty($keywords)) {
        /*
		$keywords[] = array(
          'key' => 'gmd:type',
          'value' => array('gmd:MD_KeywordTypeCode' => 'theme'),
        'attributes' => array('codeList' => 'http://www.isotc211.org/2005/resources/Codelist/gmxCodelists.xml#MD_KeywordTypeCode','codeListValue' => 'theme'),
		);
		*/
        $keywords[] = array(
          'key' => 'gmd:thesaurusName',
          'value' => array('gmd:CI_Citation' => array('gmd:title' => array('gco:CharacterString' => token_replace($settings['thesaurus_value'], array ('term' => reset($terms)))),'gmd:date gco:nilReason=unknown'=>'',))
        );
        $element = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array('gmd:descriptiveKeywords' => array('gmd:MD_Keywords' => $keywords)),
        );
      }
      break;
	  
	case 'iso19139_inspire_data_theme':
		
		foreach ($items as $delta => $item) {
			$current_value = taxonomy_term_load($item["tid"]);
			$title = $current_value->name;
			$field_term_url = $current_value->field_uri;
			$term_url = $field_term_url["und"][0]["value"];
			
			$element[$delta] = array(
				'#theme' => 'iso19139_elements',
				'#iso19139' => array(
					"gmd:keyword" => array("gmx:Anchor xlink:href='$term_url'" => $title,),
				),
			);		
		}
		
		if (!isset($current_value)) {
			$element = array(
				'#theme' => 'iso19139_elements',
				'#iso19139' => array(
					"gmd:keyword" => array("gmx:Anchor xlink:href='http://inspire.ec.europa.eu/theme/ef'" => "Environmental monitoring facilities",),
				),
			);	
		}
	  
      break; 
	  
    case 'entityreference_iso19139_element':
      $ids = array();
      foreach ($items as $item) {
        $ids[] = $item['target_id'];
      }
      if (!empty($ids) && $entities = entity_load($field['settings']['target_type'], $ids)) {
        $entities = entity_load($field['settings']['target_type'], $ids);
        $results = entity_view($field['settings']['target_type'], $entities, 'iso19139', $langcode, FALSE);
        if (!empty($settings['element']) && !empty($settings['multiple'])) {
          $element = array(
            '#theme' => 'iso19139_tags',
            '#tag' => $settings['element'],
            '#provenance' => $field['field_name'],
          ) + $results[$field['settings']['target_type']];
        }
        else {
          foreach ($results[$field['settings']['target_type']] as $delta => $result) {
            if (!empty($settings['element'])) {
              $element[$delta] = array(
                '#theme' => 'iso19139_tag',
                '#tag' => $settings['element'],
                '#value' => render($result),
              );
            }
            else {
              $element[$delta] = array(
                '#markup' => render($result),
              );
            }
          }
        }
      }
      break;

    case 'name_iso19139_individualName':
      foreach ($items as $delta => $item) {
        $name = array(
          'gco:CharacterString' => $item['given'].' '.$item['family'],
        );
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array('gmd:individualName' => $name),
        );
      }
      break;
	  

    case 'addressfield_iso19139_address':

      foreach ($items as $delta => $item) {
        $address = array();
        if (!empty($item['thoroughfare'])) {
          $address['gmd:deliveryPoint']['gco:CharacterString'] = $item['thoroughfare'];
        }
        if (!empty($item['locality'])) {
          $address['gmd:city']['gco:CharacterString'] = $item['locality'];
        }
        if (!empty($item['administrative_area'])) {
          $address['gmd:administrativeArea']['gco:CharacterString'] = $item['administrative_area'];
        }
        if (!empty($item['postal_code'])) {
          $address['gmd:postalCode']['gco:CharacterString'] = $item['postal_code'];
        }
        if (!empty($item['country'])) {
          $address['gmd:country']['gco:CharacterString'] = $item['country'];
        }
        if (!empty($item['email'])) {
          $address['gmd:electronicMailAddress']['gco:CharacterString'] = $item['email'];
        }

        // if all address-related elements, dont print an <address> group
        if (!empty($address)) {
           $element[$delta] = array(
             '#theme' => 'iso19139_elements',
             '#iso19139' => $address,
//             '#iso19139' => array('gmd:address' => array('gmd:CI_Address' => $address)),
           );
        }
      }
      break;

    case 'date_iso19139_temporalCoverage':
      $values = array();
      foreach ($items as $item) {
        $display['settings']['format_type'] = 'iso_8601';
        $dates = date_formatter_process('date_default', $entity_type, $entity, $field, $instance, $langcode, $item, $display);
        $granularity = date_granularity($field);
        if ($dates['value']['formatted'] != $dates['value2']['formatted']) {
          $value = array(
            'key' => 'gmd:EX_TemporalExtent',
            'value'  => array(
              'gmd:extent' => array('gml:TimePeriod gml:id=dle301' => 
                 array('gml:description' => 'ground condition',
                       'gml:beginPosition' => _iso19139_date_value($dates['value'], $granularity),
                       'gml:endPosition' => _iso19139_date_value($dates['value2'], $granularity),))));
        }
        else {
          $value = array(
             'key' => 'gmd:EX_TemporalExtent',
             'value' => array('gmd:extent' => array('gmd:TimeInstant' => array('gml:timePosition' => _iso19139_date_value($dates['value'], $granularity)))));
        }
        $values[] = $value;
      }

      if (!empty($values)) {
        $element = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array('gmd:temporalElement' => $values),
        );
      }
      break;

    case 'geofield_iso19139_boundingCoordinates':
      foreach ($items as $delta => $item) {
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array(
            'gmd:westBoundLongitude' => array('gco:Decimal' => $item['right']),
            'gmd:eastBoundLongitude' => array('gco:Decimal' => $item['left']),
            'gmd:southBoundLatitude' => array('gco:Decimal' => $item['bottom']),
            'gmd:northBoundLatitude' => array('gco:Decimal' => $item['top']),
          ),
        );
      }
      break;

    case 'telephone_iso19139_phone':
      foreach ($items as $delta => $item) {
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array(array(
            'key' => 'gmd:phone',
            'value' => array('gmd:CI_Telephone' => array('gmd:voice' => $item['value'])),
          )),
        );
      }
      break;
/*    case 'iso19139_electronicMailAddress':
*      foreach ($items as $delta => $item) {
*        $element[$delta] = array(
*          '#theme' => 'eml_elements',
*          '#iso19139' => array(
*            'gmd:electronicMailAddress' => array('gco:CharacterString' => $item['email']),
*          ),
*        );
*      }
*      break;
*/
    
	/*case 'iso19139_onlineUrl':
      foreach ($items as $delta => $item) {
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array(
            'gmd:contactInfo' => array('gmd:CI_Contact' => array('gmd:onlineResource' => array('gmd:CI_OnlineResource' => array('gmd:linkage'  => array('gmd:URL' => $field['type'] == 'url' ? $item['value'] : $item['url']))))),
          ),
        );
      }*/
	case 'iso19139_onlineUrl':
      foreach ($items as $delta => $item) {
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array('gmd:onlineResource' => array('gmd:CI_OnlineResource' => array('gmd:linkage'  => array('gmd:URL' => $field['type'] == 'url' ? $item['value'] : $item['url']))),
          ),
        );
      } 
      break;
	  


    case 'iso19139_text':
      foreach ($items as $delta => $item) {
        $text = _text_sanitize($instance, $langcode, $item, 'value');
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array('gco:CharacterString' => strip_tags($text),
          ),
        );
      }
      break;
	  
	  // klimeto
	  case 'iso19139_text_ipr':
      foreach ($items as $delta => $item) {
        $text = _text_sanitize($instance, $langcode, $item, 'value');
        $element[$delta] = array(
          '#theme' => 'iso19139_elements',
          '#iso19139' => array('gmd:useLimitation' => array('gco:CharacterString' => strip_tags($text)),
          ),
        );
      }
      break;

  }

  return $element;
}

function _iso19139_date_value($date, $granularity) {
  $value = $date['formatted_date'];
  if (!empty($granularity['hour']) || !empty($granularity['minute']) || !empty($granularity['second'])) {
    $value['time'] = $date['formatted_time'] . $date['formatted_timezone'];
  }
  return $value;
}
