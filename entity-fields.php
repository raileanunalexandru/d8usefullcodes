<?php

// EXTRACT CONTENT OF TEXT FIELD
if ($entity->hasField('field_text') && !$entity->get('field_text')->isEmpty()) {
  // FORMATED SINGLE AND MULTIPLE
  $text =   $text = $entity->get('field_text')->getValue();
  // PLAIN SINGLE VALUE
  $text = array_shift($entity->get('field_text')->getValue())['value'];
  // PLAIN MULTIPLE VALUE
  $texts = [];
  foreach ($entity->get('field_text')->getValue() as $text_item) {
    array_push($texts, array_shift($text_item));
  }
}

// EXTRACT LINK DATA OF LINK FIELD
if ($entity->hasField('field_link') && !$entity->get('field_link')->isEmpty()) {
  // SINGLE VALUE
  $link['url'] =  Url::fromUri($entity->get('field_link')->getValue()[0]['uri'])->toString();
  $link['title'] = $entity->get('field_link')->getValue()[0]['title'];
  // MULTIPLE VALUE
  $links = [];
  foreach ($entity->get('field_link')->getValue() as $link_item) {
    $link['url'] =  Url::fromUri($link_item['uri'])->toString;
    $link['title'] = $link_item['title'];
    array_push($links, $link);
  }
}

// EXTRACT IMAGE AND ALT
if ($entity->hasField('field_image') && !$entity->get('field_image')->isEmpty()) {
  // SINGLE VALUE
  $image['url'] = file_create_url($entity->get('field_image')->entity->getFileUri());
  $image['alt'] = $entity->get('field_image')->alt;
  // MULTIPLE VALUE
  $images = [];
  foreach ($entity->get('field_image')->referencedEntities() as $image_item) {
    $image['url'] = file_create_url($image_item->getFileUri());
    $image['alt'] = $image_item->alt;
    array_push($images, $image);
  }
}

// EXTRACT TARGET ID OF REFERENCE FIELD
if ($entity->hasField('field_reference') && !$entity->get('field_reference')->isEmpty()) {
  // SINGLE VALUE
  $target_id = $entity->get('field_reference')->getValue();
  // MULTIPLE VALUE
  $target_ids = [];
  foreach ($entity->get('field_reference')->getValue() as $referenced_item) {
    array_push($target_ids, $referenced_item['target_id']);
  }
}

// EXTRACT REFERENCED ENTITIES
if ($entity->hasField('field_reference') && !$entity->get('field_reference')->isEmpty()) {
  // SINGLE VALUE
  $target_entity = $entity->get('field_reference')->entity;
  // MULTIPLE VALUE
  $referenced_entities = [];
  foreach ($entity->get('field_reference')->referencedEntities() as $referenced_entity) {
    array_push($referenced_entities, $referenced_entity);
  }
}

// LOAD TERM FROM FIELD AND IT'S PARENTS - CREATE BRADCRUMB FROM TAXONOMY TERM
if ($entity->hasField('field_term') && !$entity->get('field_term')->isEmpty()) {
  $term = $entity->get('field_term')->entity;
  $parents = \Drupal::service('entity_type.manager')->getStorage("taxonomy_term")->loadAllParents($term->id());
  $breadcrumb = [];
  for (end($parents); key($parents)!==null; prev($parents)){
    $current = current($parents);
    $breadcrumb[] = array(
      'title' => $current->label(),
    );
  }
}
