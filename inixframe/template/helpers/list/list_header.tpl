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
{if $ajax}
<script type="text/javascript">
    $(function () {
        $(".ajax_table_link").click(function (e) {
            var link = $(this);
            $.ajax({
                type: "POST",
                url: $(this).attr('href'),
                async: true,
                success: function (data,status,jqxhr) {

                    if (data.success == 1) {
                        showFrameSuccessMessage(data.text);

                        if (link.hasClass('action-disabled'))
                            link.removeClass('action-disabled').addClass('action-enabled').removeClass('btn-danger').addClass('btn-success');
                        else
                            link.removeClass('action-enabled').addClass('action-disabled').removeClass('btn-success').addClass('btn-danger');

                        link.children().each(function () {
                            if ($(this).hasClass('hidden'))
                                $(this).removeClass('hidden');
                            else
                                $(this).addClass('hidden');
                        });
                    }
                    else
                        showFrameErrorMessage(data.text);
                },
                error: function(jqxhr, testStatus,err){

                },
                dataType: 'json'
            });
            e.preventDefault();
            return false;
        });
    });
</script>
{/if}

{if !$simple_header}
	{* Display column names and arrows for ordering (ASC, DESC) *}

    {if $is_order_position}
        <script type="text/javascript" src="{$frame_path_uri}js/jquery.tablednd.js"></script>
        <script type="text/javascript">
            var come_from = '{$list_id|addslashes}';
            var alternate = {if $order_way == 'DESC'}'1'{else}'0'{/if};
        </script>
        <script type="text/javascript" src="{$frame_path_uri}js/admin-dnd.js"></script>
    {/if}

	<script type="text/javascript">
		$(document).ready(function() {
			$('table.{$list_id} .filter').keypress(function(event){
				formSubmit(event, 'submitFilterButton{$list_id}')
			})

			$('#submitFilterButton{$list_id}').click(function() {
				$('#submitFilter{$list_id}').val(1);
			});


		});
	</script>


{/if}{* End if simple_header *}

{if $show_toolbar}
	{include file="{$frame_local_path}template/toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
{/if}

{if !$simple_header}
<div class="leadin">
	{block name="leadin"}{/block}
</div>
{/if}

{block name="override_header"}{/block}

<div class="note note-warning" id="{$list_id}-empty-filters-alert" style="display:none;">{l s='Please fill at least one field to perform a search in this list.' mod='inixframe'}</div>
{block name="startForm"}
<form method="post" action="{$action}" class="form-horizontal clearfix" id="{$list_id}">
{/block}
{if !$simple_header}
	<input type="hidden" id="submitFilter{$list_id}" name="submitFilter{$list_id}" value="1"/>
{/if}
	{block name="override_form_extra"}{/block}
	<div class="portlet col-lg-12">
		<div class="portlet-title">
            <div class="caption">
                {if isset($icon)}<i class="{$icon}"></i> {/if}{if is_array($title)}{$title|end}{else}{$title}{/if}
                <span class="badge badge-primary">{$list_total}</span>
            </div>
            {if isset($toolbar_btn) && count($toolbar_btn) >0}
            <div class="tools">
			<span class="panel-heading-action">
			{foreach from=$toolbar_btn item=btn key=k}
				{if $k != 'modules-list' && $k != 'back'}
					<a id="desc-{$table}-{if isset($btn.imgclass)}{$btn.imgclass}{else}{$k}{/if}" class="list-toolbar-btn btn btn-xs btn-default" {if isset($btn.href)}href="{$btn.href}"{/if} {if isset($btn.target) && $btn.target}target="_blank"{/if}{if isset($btn.js) && $btn.js}onclick="{$btn.js}"{/if}>
						<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s=$btn.desc}" data-html="true">
							<i class="process-icon-{if isset($btn.imgclass)}{$btn.imgclass}{else}{$k}{/if} {if isset($btn.class)}{$btn.class}{/if}" ></i>
						</span>
					</a>
				{/if}
			{/foreach}
				<a id="desc-{$table}-refresh" class="list-toolbar-btn btn btn-xs btn-default" href="javascript:location.reload();">
					<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Refresh list' mod='inixframe'}" data-html="true">
						<i class="process-icon-refresh" ></i>
					</span>
				</a>
			</span>
            </div>
			{/if}
			{if $show_toolbar}
			<script language="javascript" type="text/javascript">
			//<![CDATA[
				var submited = false

				$(function() {
					//get reference on save link
					btn_save = $('i[class~="process-icon-save"]').parent();

					//get reference on form submit button
					btn_submit = $('#{$table}_form_submit_btn');

					if (btn_save.length > 0 && btn_submit.length > 0)
					{
						//get reference on save and stay link
						btn_save_and_stay = $('i[class~="process-icon-save-and-stay"]').parent();

						//get reference on current save link label
						lbl_save = $('#desc-{$table}-save div');

						//override save link label with submit button value
						if (btn_submit.val().length > 0)
							lbl_save.html(btn_submit.attr("value"));

						if (btn_save_and_stay.length > 0)
						{

							//get reference on current save link label
							lbl_save_and_stay = $('#desc-{$table}-save-and-stay div');

							//override save and stay link label with submit button value
							if (btn_submit.val().length > 0 && lbl_save_and_stay && !lbl_save_and_stay.hasClass('locked'))
							{
								lbl_save_and_stay.html(btn_submit.val() + " {l s='and stay' mod='inixframe'} ");
							}

						}

						//hide standard submit button
						btn_submit.hide();
						//bind enter key press to validate form
						$('#{$table}_form').keypress(function (e) {
							if (e.which == 13 && e.target.localName != 'textarea')
								$('#desc-{$table}-save').click();
						});
						//submit the form
						{block name=formSubmit}
							btn_save.click(function() {
								// Avoid double click
								if (submited)
									return false;
								submited = true;
								//add hidden input to emulate submit button click when posting the form -> field name posted
								btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'" value="1" />');
								$('#{$table}_form').submit();
								return false;
							});

							if (btn_save_and_stay)
							{
								btn_save_and_stay.click(function() {
									//add hidden input to emulate submit button click when posting the form -> field name posted
									btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndStay" value="1" />');
									$('#{$table}_form').submit();
									return false;
								});
							}
						{/block}
					}
				});
			//]]>
			</script>
			{/if}
		</div>

	{block name="preTable"}{/block}
	<div class="portlet-body {if isset($use_overflow) && $use_overflow} overflow-y{/if}">
		<table
			{if $table_id} id={$table_id}{/if}
			class="table  table-hover {if $table_dnd}tableDnD{/if} {$table}"
			>
			<thead>
				<tr class="nodrag nodrop">
					<th class="center">
					</th>
					{foreach $fields_display AS $key => $params}
					<th class="{if isset($params.class)}{$params.class}{/if}{if isset($params.align)} {$params.align}{/if}">

						<span class="title_box {if isset($order_by) && ($key == $order_by)} active{/if}">

							{if isset($params.hint)}
							<span class="label-tooltip" data-toggle="tooltip"
								title="
									{if is_array($params.hint)}
										{foreach $params.hint as $hint}
											{if is_array($hint)}
												{$hint.text}
											{else}
												{$hint}
											{/if}
										{/foreach}
									{else}
										{$params.hint}
									{/if}
								">
								{$params.title}
							</span>
							{else}
								{$params.title}
							{/if}

							{if (!isset($params.orderby) || $params.orderby) && !$simple_header && $show_filters}

                                <a {if isset($order_by) && ($key == $order_by) && ($order_way == 'ASC')}class="active"{/if} href="{$currentIndex}&amp;{$list_id}Orderby={$key|urlencode}&amp;{$list_id}Orderway=asc&amp;token={$token}{if isset($smarty.get.$identifier)}&{$identifier}={$smarty.get.$identifier|intval}{/if}">
                                    <i class="icon-caret-up"></i>
                                </a>
                                <a {if isset($order_by) && ($key == $order_by) && ($order_way == 'DESC')}class="active"{/if}  href="{$currentIndex}&amp;{$list_id}Orderby={$key|urlencode}&amp;{$list_id}Orderway=desc&amp;token={$token}{if isset($smarty.get.$identifier)}&{$identifier}={$smarty.get.$identifier|intval}{/if}">
                                    <i class="icon-caret-down"></i>
                                </a>



							{/if}
						</span>
					</th>
					{/foreach}
					{if $shop_link_type}
					<th>
						<span class="title_box">
						{if $shop_link_type == 'shop'}
							{l s='Shop' mod='inixframe'}
						{else}
							{l s='Group shop' mod='inixframe'}
						{/if}
						</span>
					</th>
					{/if}
					{if $has_actions || $show_filters}
					<th >{if !$simple_header}{/if}</th>
					{/if}
				</tr>
			{if (!$simple_header && $show_filters)}
				<tr class="nodrag nodrop filter {if $row_hover}row_hover{/if}">
					<th class="text-center">
						{if $has_bulk_actions}

						{/if}
					</th>
					{* Filters (input, select, date or bool) *}
					{foreach $fields_display AS $key => $params}
					<th {if isset($params.align)} class="{$params.align}" {/if}  {if isset($params.width) && $params.width != 'auto'} style="width:{$params.width}px"{/if}>
						{if isset($params.search) && !$params.search}

						{else}
							{if $params.type == 'bool'}
								<select class="form-control input-sm" name="{$list_id}Filter_{if isset($params.filter_key)}{$params.filter_key}{else}{$key}{/if}" onchange="$('#submitFilterButton{$list_id}').focus();$('#submitFilterButton{$list_id}').click();" >
									<option value="">-</option>
									<option value="1" {if $params.value == 1} selected="selected" {/if}>{l s='Yes' mod='inixframe'}</option>
									<option value="0" {if $params.value == 0 && $params.value != ''} selected="selected" {/if}>{l s='No' mod='inixframe'}</option>
								</select>
							{elseif $params.type == 'date' || $params.type == 'datetime'}
								<div class="date_range {if $params.type == 'datetime'}mydatetime-picker{else}date-picker{/if} input-daterange framedatepicker" data-date-format="yyyy-mm-dd">
									<div class="input-group fixed-width-lg row-margin-bottom">
										<input type="text" class="filter date-input form-control input-sm" id="{$params.id_date}_0" name="{$params.name_date}[0]" value="{if isset($params.value.0)}{$params.value.0}{/if}" placeholder="{l s='From' mod='inixframe'}"/>
										<span class="input-group-addon">
											<i class="icon-calendar"></i>
										</span>
									</div>
									<div class="input-group fixed-width-lg">
										<input type="text" class="filter  date-input form-control input-sm framedatepicker" id="{$params.id_date}_1" name="{$params.name_date}[1]" value="{if isset($params.value.1)}{$params.value.1}{/if}" placeholder="{l s='To' mod='inixframe'}" />
										<span class="input-group-addon">
											<i class="icon-calendar"></i>
										</span>
									</div>
								</div>
							{elseif $params.type == 'select'}
								{if isset($params.filter_key)}
									<select class="form-control input-sm" onchange="$('#submitFilterButton{$list_id}').focus();$('#submitFilterButton{$list_id}').click();" name="{$list_id}Filter_{$params.filter_key}" >
										<option value="" {if $params.value == ''} selected="selected" {/if}>-</option>
										{if isset($params.list) && is_array($params.list)}
											{foreach $params.list AS $option_value => $option_display}
												<option value="{$option_value}" {if (string)$option_display === (string)$params.value ||  (string)$option_value === (string)$params.value} selected="selected"{/if}>{$option_display}</option>
											{/foreach}
										{/if}
									</select>
								{/if}
							{else}
								<input type="text" class="form-control input-sm" name="{$list_id}Filter_{if isset($params.filter_key)}{$params.filter_key}{else}{$key}{/if}" value="{$params.value|escape:'html':'UTF-8'}" />
							{/if}
						{/if}
					</th>
					{/foreach}

					{if $shop_link_type}
					<th>--</th>
					{/if}
					{if $has_actions || $show_filters }
					<th class="actions">
						{if $show_filters || $has_set_filters}
						<span class="pull-right">
							{*Search must be before reset for default form submit*}
							<button type="submit" id="submitFilterButton{$list_id}" name="submitFilter" class="btn btn-default" data-list-id="{$list_id}">
								<i class="icon-search"></i> {l s='Search' mod='inixframe'}
							</button>
							{if $filters_has_value}
							<button type="submit" name="submitReset{$list_id}" class="btn btn-warning">
								<i class="icon-eraser"></i> {l s='Reset' mod='inixframe'}
							</button>
							{/if}
						</span>
						{/if}
					</th>
					{/if}
				</tr>
			{/if}
			</thead>