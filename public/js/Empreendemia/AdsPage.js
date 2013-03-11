/** 
 * AdsPage.js
 * Controla as abas da página de publicidade
 * 
 * @package Empreendemia
 * @author Rafael Almeida Erthal Hermano
 * @since 2012-03
 */

var AdsPage = function(){}

AdsPage.showTab = function(tabname) {
    if (tabname != '') {
        $('.showtab').hide();
        $('.tabs li').removeClass('selected');
        $('.tabs li.tab_'+tabname).addClass('selected');
        $('.showtab_'+tabname).fadeIn();
    }
    if (tabname == 'descricao') {
        //<?php echo $this->Tracker()->userEvent('ads: viewed description', null, true) ?>
    }
    else if (tabname == 'depoimentos') {
        //<?php echo $this->Tracker()->userEvent('ads: viewed testimonials', null, true) ?>
    }
    else if (tabname == 'tabela-de-precos') {
        //<?php echo $this->Tracker()->userEvent('ads: viewed pricing', null, true) ?>
    }
    else if (tabname == 'duvidas-comuns') {
        //<?php echo $this->Tracker()->userEvent('ads: viewed faq', null, true) ?>
    }
    else {
        //<?php echo $this->Tracker()->userEvent('ads: viewed description', null, true) ?>
    }
 }

AdsPage.load = function() {
    AdsPage.showTab(window.location.hash.substring(1));
    $('.tab_descricao').click(function(){AdsPage.showTab('descricao')});
    $('.tab_depoimentos').click(function(){AdsPage.showTab('depoimentos')});
    $('.tab_tabela-de-precos').click(function(){AdsPage.showTab('tabela-de-precos')});
    $('.tab_duvidas-comuns').click(function(){AdsPage.showTab('duvidas-comuns')});
}