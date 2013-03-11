/** 
 * Premium.js
 * Controla as abas na tela de propagando do empreendemia premium
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var Premium = function(){}

Premium.showTab = function(tabname) {
    if (tabname != '') {
        $('.showtab').hide();
        $('.tabs li').removeClass('selected');
        $('.tabs li.tab_'+tabname).addClass('selected');
        $('.showtab_'+tabname).fadeIn();
    }
    if (tabname == 'beneficios') {
        //<?php echo $this->Tracker()->userEvent('premium: viewed description', null, true) ?>
    }
    else if (tabname == 'comparativo') {
        //<?php echo $this->Tracker()->userEvent('premium: viewed comparative table', null, true) ?>
    }
    else if (tabname == 'tabela-de-precos') {
        //<?php echo $this->Tracker()->userEvent('premium: viewed pricing', null, true) ?>
    }
    else if (tabname == 'duvidas-comuns') {
        //<?php echo $this->Tracker()->userEvent('premium: viewed faq', null, true) ?>
    }
    else {
        //<?php echo $this->Tracker()->userEvent('premium: viewed benefits', null, true) ?>
    }
 }

Premium.load = function() {
    AdsPage.showTab(window.location.hash.substring(1));
    $('.tab_beneficios').click(function(){AdsPage.showTab('beneficios')});
    $('.tab_comparativo').click(function(){AdsPage.showTab('comparativo')});
    $('.tab_tabela-de-precos').click(function(){AdsPage.showTab('tabela-de-precos')});
    $('.tab_duvidas-comuns').click(function(){AdsPage.showTab('duvidas-comuns')});
}