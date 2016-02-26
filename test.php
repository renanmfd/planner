<?php

init_session();
check_logged();
require_once 'php/classes/Form.class.php';

header('Content-Type: text/html');
echo '<!DOCTYPE html>';
echo '<head><title>Test</title>';
echo '<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">';
echo '</head><body>';

echo 'Test';

$form = new Form();

$container = new FormContainer('div', array('container'));

/*
$row = new FormContainer();
$row->attr('class', 'row');

$leftside = new FormContainer();
$leftside->attr('class', 'col-xs-6');

$rightside = new FormContainer();
$rightside->attr('class', 'col-xs-6');
*/
$name = new FormItem('Name', 'text');
$name->id('registerName')
    ->attr('data-validation', 'name')
    ->required();

$email = new FormItem('Email', 'email');
$name->id('registerEmail')
    ->required();

$type = new FormItem('Type', 'select');
$type->id('registerName')
    ->option(0, 'Nenhum')
    ->option(1, 'Programador')
    ->option(2, 'Analista')
    ->option(3, 'Suporte')
    ->option(4, 'Comercial')
    ->option(5, 'Financeiro');

$color = new FormItem('Theme', 'color');
$color->id('registerTheme');

$date = new FormItem('Date', 'date');
$date->id('registerDate');

$newsletter = new FormItem('Newsletter', 'checkbox');
$newsletter->id('registerNewsletter');

$submit = new FormItem('Submit', 'submit');
$submit->id('registerSubmit');

$container
    ->add($name)
    ->add($email)
    ->add($type)
    ->add($color)
    ->add($date)
    ->add($newsletter)
    ->add($submit);

$form->add($container);

echo $form->generate();

krumo($form);

echo '</body>';
