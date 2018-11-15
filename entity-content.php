<?php

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

// LOAD VIEW WITH PARAMETER FOR CONTEXTUAL FILTER
$view_name = 'view';
$view_display = 'view_display';
$view_parameter = 'parameter';
$view = \Drupal\views\Views::getView($view_name);
if (is_object($view) && !empty($view_parameter)) {
  $renderable_view = $view->buildRenderable($view_display, array($view_parameter));
}

// REBUILD MENU (MENU TREE) INTO AN ASSOCIATIVE ARRAY
function rebuildMenu($tree) {
  $menu = [];
  $menu_link = [];
  foreach ($tree as $key => $element) {
    // Extract the details for this item.
    $title = $element->link->getTitle();
    if ($element->link->getUrlObject()->isRouted()) {
      $url = Url::fromRoute(
        $element->link->getRouteName(),
        $element->link->getRouteParameters(),
        $element->link->getOptions()
      )->toString();
    }
    else {
      $url = Url::fromUri($element->link->getUrlObject()->getUri())->toString();
    }
    $link_attributes = (isset($element->link->getPluginDefinition()['options']['attributes']) ?
      $element->link->getPluginDefinition()['options']['attributes'] :
      []);

    $menu_link = [
      'title' => $title,
      'url' => $url,
      'attributes' => $link_attributes,
    ];

    if ($element->hasChildren && null !== $element->link && !$element->link instanceof InaccessibleMenuLink) {
      $menu_tree = \Drupal::menuTree();
      $parameters = new MenuTreeParameters();
      $parameters->setRoot($element->link->getPluginId())->excludeRoot()->setMaxDepth(1)->onlyEnabledLinks();
      $subtree = $menu_tree->load(NULL, $parameters);
      if ($subtree) {
        $menu_link['children'] = expandAll($subtree);
      }
    }
    array_push($menu, $menu_link);
  }
  return $menu;
}
