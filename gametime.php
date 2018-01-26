<?php

require 'config.php';

dol_include_once('/contact/class/contact.class.php');
dol_include_once('/gametime/class/gametime.class.php');

$object = new Contact($db);
$object->fetch(GETPOST('fk_contact'));

$action = GETPOST('action');
$gametime = new GameTime($db);


switch ($action) {
    case 'save':

        $gametime->setValues($_POST);
        if($gametime->id>0) $gametime->update($user);
        else $gametime->create($user);

        setEventMessage('Element jeu sauvegardé');

        _card($object,$gametime);
        break;
    default:
        _card($object,$gametime);
        break;
}

function _card(&$object,&$gametime) {
    global $db,$conf,$langs;

    dol_include_once('/core/lib/contact.lib.php');

    llxHeader();
    $head = contact_prepare_head($object);
    dol_fiche_head($head, 'tab14995', '', 0, '');

    $formCore=new TFormCore('gametime.php', 'formGameTime');
    echo $formCore->hidden('fk_contact', $object->id);
    echo $formCore->hidden('action', 'save');

    echo '<h2>Ajout d\'un élément jeu.</h2>';

    echo $formCore->texte('Nom du jeu','title',$gametime->title,80,255).'<br />';
    echo $formCore->texte('Temps de jeu (en h)','time',$gametime->time,80,255).'<br />';

    echo $formCore->btsubmit('Ajouter', 'bt_save');

    $formCore->end();

    _list($object, $gametime);

    dol_fiche_end();
    llxFooter();

}

function _list($object, $gametime) {
    global $langs;
    $PDOdb = new TPDOdb;

    $gametime->fetchByContact($object->id);

    $l=new TListviewTBS('lGameTime');
    $sql =  $gametime->fetchByContact($object->id);

    echo $l->render($PDOdb, $sql,array(

        'title'=>array(
            'title'=> $langs->trans('nomJeux'),
            'time'=> $langs->trans('tempsJeux'),
            'tms'=> 'Date'
        )
    ,'link'=>array(
            'Action'=>'<a href="?id='.$object->id.'&action=del&rowid=@rowid@">TEST</a>'
        )
    ,'hide'=>array('rowid')
    ,'type'=>array(
            'tms'=>'date'
        )
    ));

}