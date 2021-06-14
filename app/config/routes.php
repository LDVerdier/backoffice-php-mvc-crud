<?php

//Routes UserController
$router->map(
  'GET',
  '/user/login',
  [
      'method' => 'login',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-login'
);

$router->map(
  'POST',
  '/user/login',
  [
      'method' => 'loginPost',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-loginPost'
);

$router->map(
  'GET',
  '/user/logout',
  [
      'method' => 'logout',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-logout'
);

$router->map(
  'GET',
  '/user/list',
  [
      'method' => 'list',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-list'
);

$router->map(
  'GET',
  '/user/add',
  [
      'method' => 'add',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-add'
);

$router->map(
  'POST',
  '/user/add',
  [
      'method' => 'addPost',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-addPost'
);

$router->map(
  'GET',
  '/user/update/[i:userId]',
  [
      'method' => 'update',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-update'
);

$router->map(
  'POST',
  '/user/update',
  [
      'method' => 'updatePost',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-updatePost'
);

$router->map(
  'GET',
  '/user/delete/[i:userId]',
  [
      'method' => 'delete',
      'controller' => '\App\Controllers\UserController'
  ],
  'user-delete'
);

//Routes MainController
$router->map(
  'GET',
  '/',
  [
      'method' => 'home',
      'controller' => '\App\Controllers\MainController'
  ],
  'main-home'
);

//Routes CatalogController
$router->map(
  'GET',
  '/category/list',
  [
      'method' => 'list',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-list'
);

$router->map(
  'GET',
  '/category/add',
  [
      'method' => 'add',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-add'
);

$router->map(
  'POST',
  '/category/add',
  [
      'method' => 'addPost',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-addPost'
);

$router->map(
  'GET',
  '/category/update/[i:categoryId]',
  [
      'method' => 'update',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-update'
);

$router->map(
  'POST',
  '/category/update',
  [
      'method' => 'updatePost',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-updatePost'
);

$router->map(
  'GET',
  '/category/delete/[i:categoryId]',
  [
      'method' => 'delete',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-delete'
);

$router->map(
  'GET',
  '/category/home-order',
  [
      'method' => 'homeOrder',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-homeOrder'
);

$router->map(
  'POST',
  '/category/home-order',
  [
      'method' => 'homeOrderPost',
      'controller' => '\App\Controllers\CategoryController'
  ],
  'category-homeOrderPost'
);

//Routes ProductController
$router->map(
  'GET',
  '/product/list',
  [
      'method' => 'list',
      'controller' => '\App\Controllers\ProductController'
  ],
  'product-list'
);

$router->map(
  'GET',
  '/product/add',
  [
      'method' => 'add',
      'controller' => '\App\Controllers\ProductController'
  ],
  'product-add'
);

$router->map(
  'POST',
  '/product/add',
  [
      'method' => 'addPost',
      'controller' => '\App\Controllers\ProductController'
  ],
  'product-addPost'
);

$router->map(
  'GET',
  '/product/update/[i:productId]',
  [
      'method' => 'update',
      'controller' => '\App\Controllers\ProductController'
  ],
  'product-update'
);

$router->map(
  'POST',
  '/product/update',
  [
      'method' => 'updatePost',
      'controller' => '\App\Controllers\ProductController'
  ],
  'product-updatePost'
);

$router->map(
  'GET',
  '/product/delete/[i:productId]',
  [
      'method' => 'delete',
      'controller' => '\App\Controllers\ProductController'
  ],
  'product-delete'
);

//Routes BrandController
$router->map(
  'GET',
  '/brand/list',
  [
      'method' => 'list',
      'controller' => '\App\Controllers\BrandController'
  ],
  'brand-list'
);

$router->map(
  'GET',
  '/brand/add',
  [
      'method' => 'add',
      'controller' => '\App\Controllers\BrandController'
  ],
  'brand-add'
);

$router->map(
  'POST',
  '/brand/add',
  [
      'method' => 'addPost',
      'controller' => '\App\Controllers\BrandController'
  ],
  'brand-addPost'
);

$router->map(
  'GET',
  '/brand/update/[i:brandId]',
  [
      'method' => 'update',
      'controller' => '\App\Controllers\BrandController'
  ],
  'brand-update'
);

$router->map(
  'POST',
  '/brand/update',
  [
      'method' => 'updatePost',
      'controller' => '\App\Controllers\BrandController'
  ],
  'brand-updatePost'
);

$router->map(
  'GET',
  '/brand/delete/[i:brandId]',
  [
      'method' => 'delete',
      'controller' => '\App\Controllers\BrandController'
  ],
  'brand-delete'
);

//Routes TypeController
$router->map(
  'GET',
  '/type/list',
  [
      'method' => 'list',
      'controller' => '\App\Controllers\TypeController'
  ],
  'type-list'
);

$router->map(
  'GET',
  '/type/add',
  [
      'method' => 'add',
      'controller' => '\App\Controllers\TypeController'
  ],
  'type-add'
);

$router->map(
  'POST',
  '/type/add',
  [
      'method' => 'addPost',
      'controller' => '\App\Controllers\TypeController'
  ],
  'type-addPost'
);

$router->map(
  'GET',
  '/type/update/[i:typeId]',
  [
      'method' => 'update',
      'controller' => '\App\Controllers\TypeController'
  ],
  'type-update'
);

$router->map(
  'POST',
  '/type/update',
  [
      'method' => 'updatePost',
      'controller' => '\App\Controllers\TypeController'
  ],
  'type-updatePost'
);

$router->map(
  'GET',
  '/type/delete/[i:typeId]',
  [
      'method' => 'delete',
      'controller' => '\App\Controllers\TypeController'
  ],
  'type-delete'
);
