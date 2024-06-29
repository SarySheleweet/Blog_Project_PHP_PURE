<?php
declare(strict_types=1);
 require_once './utils/errors.php';

$errors = [
  'title' => '',
  'image' => '',
  'category' => '',
  'content' => ''
];

$_POST = filter_input_array(INPUT_POST, [
    'title' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'image' => FILTER_SANITIZE_URL,
    'category' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
    'content' => [
      'filter' => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
      'flags' => FILTER_FLAG_NO_ENCODE_QUOTES
    ]
  ]);

  $title = $_POST['title'] ?? '';
  $image = $_POST['image'] ?? '';
  $category = $_POST['category'] ?? '';
  $content = $_POST['content'] ?? '';
  $availableCategories = ['nature', 'technology', 'politics', 'litterature', 'science'];
  

  if (!$title) {
    $errors['title'] = ERROR_REQUIRED;
  } elseif (mb_strlen($title) < 5) {
    $errors['title'] = ERROR_TITLE_TOO_SHORT;
  } elseif (mb_strlen($title) > 50) {
    $errors['title'] = ERROR_TITLE_TOO_LONG;
  } elseif (array_search(mb_strtolower($title), array_map(fn ($el) => mb_strtolower($el['title']), $articles))) {
    $errors['title'] = ERROR_TITLE_ALREADY_EXISTS;
  }

  if (!$image) {
    $errors['image'] = ERROR_REQUIRED;
  } elseif (!filter_var($image, FILTER_VALIDATE_URL)) {
    $errors['image'] = ERROR_IMAGE_URL;
  } elseif (mb_strlen($image) > 150) {
    $errors['image'] = ERROR_URL_TOO_LONG;
  }

  if (!$category) {
    $errors['category'] = ERROR_REQUIRED;
  } elseif (!in_array($category, $availableCategories)) {
    $error['category'] = ERROR_CATEGORY_DOES_NOT_EXIST;
  }

  if (!$content) {
    $errors['content'] = ERROR_REQUIRED;
  } elseif (mb_strlen($content) < 50) {
    $errors['content'] = ERROR_CONTENT_TOO_SHORT;
  } elseif (mb_strlen($content) > 1200 ) {
    $errors['content'] = ERROR_CONTENT_TOO_LONG;
  }