<?php
/*
Plugin Name: UWriteAI Plugin
Description: Plugin para gerar artigos com API
Version: 1.0
Author: João Peixoto
Author URI: https://jpex.dev/
*/

// Add submenu to the WordPress admin menu
function UWriteAI_add_menu()
{
  add_menu_page(
    'UWriteAI Text Generator', // page title
    'UWriteAI', // menu title
    'manage_options', // capability
    'UWriteAI', // menu slug
    'UWriteAI_display_page_en', // callback function
    plugins_url( 'ai_post/images/Logotype_UWriteAi_04.png' )
  );
}
add_action('admin_menu', 'UWriteAI_add_menu');

// Adiciona subpágina oculta ao menu
function pt_menu()
{
  add_submenu_page(
    'non-existent-slug', // slug que não existe
    'UWriteAI_pt', // título da página
    'UWriteAI_pt', // nome do menu
    'manage_options', // permissões necessárias para ver a página
    'pt.php', // slug da página
    'pt_callback' // função de callback para exibir a página
  );
}
add_action('admin_menu', 'pt_menu');

function en_menu()
{
  add_submenu_page(
    'non-existent-slug', // slug que não existe
    'UWriteAI_en', // título da página
    'UWriteAI_en', // nome do menu
    'manage_options', // permissões necessárias para ver a página
    'en.php', // slug da página
    'en_callback' // função de callback para exibir a página
  );
}
add_action('admin_menu', 'en_menu');

function fr_menu()
{
  add_submenu_page(
    'non-existent-slug', // slug que não existe
    'UWriteAI_fr', // título da página
    'UWriteAI_fr', // nome do menu
    'manage_options', // permissões necessárias para ver a página
    'fr.php', // slug da página
    'fr_callback' // função de callback para exibir a página
  );
}
add_action('admin_menu', 'fr_menu');


function UWriteAI_display_page_en()
{
  include('en.php');
}
function pt_callback()
{
  include('pt.php');
}

function en_callback()
{
  include('en.php');
}

function fr_callback()
{
  include('fr.php');
}
