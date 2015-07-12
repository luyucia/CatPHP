<?php /* Smarty version Smarty-3.1.21-dev, created on 2015-06-02 18:24:13
         compiled from "views\document.html" */ ?>
<?php /*%%SmartyHeaderCode:14566556d844d0660a5-86150629%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '553a6552c5995b6c3e5e4ade6400348efca16d79' => 
    array (
      0 => 'views\\document.html',
      1 => 1432866207,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14566556d844d0660a5-86150629',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'docs' => 0,
    'd' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.21-dev',
  'unifunc' => 'content_556d844d170d30_55037735',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_556d844d170d30_55037735')) {function content_556d844d170d30_55037735($_smarty_tpl) {?><!doctype html>
<html lang="en" ng-app="catDocApp">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="document for catphp">

    <title>Document For CatPHP</title>

    <link rel="stylesheet" href="css/pure-min.css">
    <link rel="stylesheet" href="css/side-menu.css">
    <link rel="stylesheet" href="css/blue.css">
    <link rel="stylesheet" href="google-code-prettify/prettify.css">
</head>
<body onload="prettyPrint()">
    <div id="layout">
        <a href="#menu" id="menuLink" class="menu-link">
            <span></span>
        </a>
        <div id="menu" ng-controller="docListCtrl">
            <div class="pure-menu">
                <a class="pure-menu-heading" href="/">CatPHP</a>
                <ul class="pure-menu-list">
                <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['docs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
                <li  class="pure-menu-item"><a href="#<?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
" class="pure-menu-link"><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</a></li>
                <?php } ?>
                </ul>
            </div>
        </div>

        <?php  $_smarty_tpl->tpl_vars['d'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['d']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['docs']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['d']->key => $_smarty_tpl->tpl_vars['d']->value) {
$_smarty_tpl->tpl_vars['d']->_loop = true;
?>
        <div class="header">
            <h1 ><a name="<?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
"><?php echo $_smarty_tpl->tpl_vars['d']->value['name'];?>
</a></h1>
            <h2>Start your next web project with Pure.</h2>
        </div>
        <div class="content">
            <?php echo $_smarty_tpl->tpl_vars['d']->value['content'];?>

        </div>
        <?php } ?>





    <input type="hidden" id="project_id" value="555ede4815d55cdc2900003f" />

</body>
</html>
<?php echo '<script'; ?>
 type="text/javascript" src="google-code-prettify/prettify.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript" src="js/jquery-1.8.3.min.js"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 type="text/javascript">
    $('pre').addClass('prettyprint')
<?php echo '</script'; ?>
>
<?php }} ?>
