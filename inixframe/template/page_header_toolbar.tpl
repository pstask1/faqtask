{*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{* retro compatibility *}
{if !isset($title) && isset($page_header_toolbar_title)}
	{assign var=title value=$page_header_toolbar_title}
{/if}
{if isset($page_header_toolbar_btn)}
	{assign var=toolbar_btn value=$page_header_toolbar_btn}
{/if}

<div class="bootstrap">
	<div class="page-head">
		{block name=pageTitle}
		<h2 class="page-title">
			{*if isset($toolbar_btn['back'])}
			<a id="page-header-desc-{$table}{if isset($toolbar_btn['back'].imgclass)}-{$toolbar_btn['back'].imgclass}{/if}" class="page-header-toolbar-back" {if isset($toolbar_btn['back'].href)}href="{$toolbar_btn['back'].href}"{/if} title="{$toolbar_btn['back'].desc}" {if isset($toolbar_btn['back'].target) && $toolbar_btn['back'].target}target="_blank"{/if}{if isset($toolbar_btn['back'].js) && $toolbar_btn['back'].js}onclick="{$toolbar_btn['back'].js}"{/if}>
				
			</a>
			{/if*}
			{if is_array($title)}{$title|end|escape}{else}{$title|escape}{/if}
		</h2>
		{/block}

		{block name=pageBreadcrumb}
		<ul class="breadcrumb page-breadcrumb">

			{* Shop *}
			{*{if $is_multishop && $shop_list && ($multishop_context & Shop::CONTEXT_GROUP || $multishop_context & Shop::CONTEXT_SHOP)}*}
				{*<li class="breadcrumb-multishop">*}
					{*{$shop_list}*}
				{*</li>*}
			{*{/if}*}

			{* Container *}
			{if $breadcrumbs3.container.name != ''}
				<li class="breadcrumb-container">
					{if $breadcrumbs3.container.href != ''}<a href="{$breadcrumbs3.container.href|escape}">{/if}
					{if $breadcrumbs3.container.icon != ''}<i class="{$breadcrumbs3.container.icon|escape}"></i>{/if}
					{$breadcrumbs3.container.name|escape}
					{if $breadcrumbs3.container.href != ''}</a>{/if}
				</li>
			{/if}
			
			{* Current Tab *}
			{if $breadcrumbs3.tab.name != '' && $breadcrumbs3.container.name != $breadcrumbs3.tab.name}
				<li class="breadcrumb-current">
					{if $breadcrumbs3.tab.href != ''}<a href="{$breadcrumbs3.tab.href|escape}">{/if}
					{if $breadcrumbs3.tab.icon != ''}<i class="{$breadcrumbs3.tab.icon|escape}"></i>{/if}
					{$breadcrumbs3.tab.name|escape}
					{if $breadcrumbs3.tab.href != ''}</a>{/if}
				</li>
			{/if}
			
			{* Action *}
			{*if $breadcrumbs3.action.name != ''}
				<li class="breadcrumb-action">
					{if $breadcrumbs3.action.href != ''}<a href="{$breadcrumbs3.action.href|escape}">{/if}
					{if $breadcrumbs3.action.icon != ''}<i class="{$breadcrumbs3.action.icon|escape}"></i>{/if}
					{$breadcrumbs3.action.name|escape}
					{if $breadcrumbs3.action.href != ''}</a>{/if}
				</li>
			{/if*}
			</ul>
		{/block}

		{block name=toolbarBox}
		<div class="page-bar toolbarBox">
			<div class="btn-toolbar">
				<a href="#" class="toolbar_btn dropdown-toolbar" class="navbar-toggle" data-toggle="collapse" data-target="#toolbar-nav"><i class="process-icon-dropdown"></i><span>{l s='Menu' mod='inixframe'}</span></a>
				<ul id="toolbar-nav" class="nav nav-pills pull-right collapse navbar-collapse">
					{foreach from=$toolbar_btn item=btn key=k}
					{if $k != 'back' && $k != 'modules-list'}
					<li>
						<a id="page-header-desc-{$table}-{if isset($btn.imgclass)}{$btn.imgclass|escape}{else}{$k}{/if}" class="toolbar_btn" {if isset($btn.href)}href="{$btn.href|escape}"{/if} title="{$btn.desc|escape}" {if isset($btn.target) && $btn.target}target="_blank"{/if}{if isset($btn.js) && $btn.js}onclick="{$btn.js}"{/if}>
							<i class="{if isset($btn.icon)}{$btn.icon|escape}{else}process-icon-{if isset($btn.imgclass)}{$btn.imgclass|escape}{else}{$k}{/if}{/if} {if isset($btn.class)}{$btn.class|escape}{/if}" ></i>
							<span {if isset($btn.force_desc) && $btn.force_desc == true } class="locked" {/if}>{$btn.desc|escape}</span>
						</a>
					</li>
					{/if}
					{/foreach}
					{if isset($toolbar_btn['modules-list'])}
					<li>
						<a id="page-header-desc-{$table}-{if isset($toolbar_btn['modules-list'].imgclass)}{$toolbar_btn['modules-list'].imgclass}{else}modules-list{/if}" class="toolbar_btn{if isset($toolbar_btn['modules-list'].class)} {$toolbar_btn['modules-list'].class}{/if}" {if isset($toolbar_btn['modules-list'].href)}href="{$toolbar_btn['modules-list'].href}"{/if} title="{$toolbar_btn['modules-list'].desc}" {if isset($toolbar_btn['modules-list'].target) && $toolbar_btn['modules-list'].target}target="_blank"{/if}{if isset($toolbar_btn['modules-list'].js) && $toolbar_btn['modules-list'].js}onclick="{$toolbar_btn['modules-list'].js}"{/if}>
							<i class="{if isset($toolbar_btn['modules-list'].icon)}{$toolbar_btn['modules-list'].icon}{else}process-icon-{if isset($toolbar_btn['modules-list'].imgclass)}{$toolbar_btn['modules-list'].imgclass}{else}modules-list{/if}{/if}" ></i>
							<span {if isset($toolbar_btn['modules-list'].force_desc) && $toolbar_btn['modules-list'].force_desc == true } class="locked" {/if}>{$toolbar_btn['modules-list'].desc}</span>
						</a>
					</li>
					{/if}
					{if isset($help_link)}
					<li>
						<a class="toolbar_btn btn-help" href="{$help_link|escape}" title="{l s='Help' mod='inixframe'}">
							<i class="process-icon-help"></i>
							<div>{l s='Help' mod='inixframe'}</div>
						</a>
					</li>
					{/if}
				</ul>

				<script language="javascript" type="text/javascript">
				//<![CDATA[
					var modules_list_loaded = false;

					$(function() {

						{if isset($tab_modules_open) && $tab_modules_open}
							$('#modules_list_container').modal('show');
							openModulesList();
						{/if}
					});

					{if isset($tab_modules_list)}
						$('.process-icon-modules-list').parent('a').unbind().bind('click', function (){
							$('#modules_list_container').modal('show');
							openModulesList();
						});
					{/if}
				//]]>
				</script>				
			</div>
		</div>
		{/block}
	</div>
</div>
