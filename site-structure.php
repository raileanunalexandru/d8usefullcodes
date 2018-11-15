<?php

// INSTALL A BLOCK AND SET IT IN A THEME REGION
$config_path = drupal_get_path('theme', 'THEME_NAME') . '/config/install';
$source = new FileStorage($config_path);
$config_storage = \Drupal::service('config.storage');
$files = [
  'block.block.BLOCK_ID',
];

foreach($files as $file){
  $config_storage->write($file, $source->read($file));
}

$block = Block::load('BLOCK_ID');
$block->setRegion('REGION_NAME');
$block->save();
