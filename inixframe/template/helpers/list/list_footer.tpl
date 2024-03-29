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
	</table>

<div class="row">
	<div class="col-lg-8">
		{if $bulk_actions && $has_bulk_actions}
		<div class="btn-group bulk-actions">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{l s='Bulk actions' mod='inixframe'} <span class="caret"></span>
			</button>
			<ul class="dropdown-menu">
			<li>
				<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id}Box[]', true);return false;">
					<i class="icon-check-sign"></i>&nbsp;{l s='Select all' mod='inixframe'}
				</a>
			</li>
			<li>
				<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id}Box[]', false);return false;">
					<i class="icon-check-empty"></i>&nbsp;{l s='Unselect all' mod='inixframe'}
				</a>
			</li>
			<li class="divider"></li>
			{foreach $bulk_actions as $key => $params}
				<li{if $params.text == 'divider'} class="divider"{/if}>
					{if $params.text != 'divider'}
					<a href="#" onclick="{if isset($params.confirm)}if (confirm('{$params.confirm}')){/if}sendBulkAction($(this).closest('form').get(0), 'submitBulk{$key}{$table}');">
						{if isset($params.icon)}<i class="{$params.icon}"></i>{/if}&nbsp;{$params.text}
					</a>
					{/if}
				</li>
			{/foreach}
			</ul>
		</div>
		{/if}
	</div>
	{if !$simple_header && $list_total > $pagination[0]}
	<div class="col-lg-4">
		{* Choose number of results per page *}
		<span class="pagination">
			{l s='Display' mod='inixframe'}:
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				{$selected_pagination}
				<i class="icon-caret-down"></i>
			</button>
			<ul class="dropdown-menu">
			{foreach $pagination AS $value}
				<li>
					<a href="javascript:void(0);" class="pagination-items-page" data-items="{$value|intval}" data-list-id="{$list_id}">{$value}</a>
				</li>
			{/foreach}
			</ul>
			/ {$list_total} {l s='result(s)' mod='inixframe'}
			<input type="hidden" id="{$list_id}-pagination-items-page" name="{$list_id}_pagination" value="{$selected_pagination|intval}" />
		</span>
		<script type="text/javascript">
			$('.pagination-items-page').on('click',function(e){
				e.preventDefault();
				$('#'+$(this).data("list-id")+'-pagination-items-page').val($(this).data("items")).closest("form").submit();
			});
		</script>
		<ul class="pagination pull-right">
			<li {if $page <= 1}class="disabled"{/if}>
				<a href="javascript:void(0);" class="previous pagination-link" data-page="1" data-list-id="{$list_id}">
					<i class="icon-double-angle-left"></i>&nbsp;
				</a>
			</li>
			<li {if $page <= 1}class="disabled"{/if}>
				<a href="javascript:void(0);" class="previous pagination-link" data-page="{$page - 1}" data-list-id="{$list_id}">
					<i class="icon-angle-left"></i>&nbsp;
				</a>
			</li>
			{assign p 0}
			{while $p++ < $total_pages}
				{if $p < $page-2}
					<li class="disabled">
						<a href="javascript:void(0);">&hellip;</a>
					</li>
					{assign p $page-3}
				{elseif $p > $page+2}
					<li class="disabled">
						<a href="javascript:void(0);">&hellip;</a>
					</li>
					{assign p $total_pages}
				{else}
					<li {if $p == $page}class="active"{/if}>
						<a href="javascript:void(0);" class="pagination-link" data-page="{$p}" data-list-id="{$list_id}">{$p}</a>
					</li>
				{/if}
			{/while}
			<li {if $page >= $total_pages}class="disabled"{/if}>
				<a href="javascript:void(0);" class="next pagination-link" data-page="{$page + 1}" data-list-id="{$list_id}">
					<i class="icon-angle-right"></i>&nbsp;
				</a>
			</li>
			<li {if $page >= $total_pages}class="disabled"{/if}>
				<a href="javascript:void(0);" class="next pagination-link" data-page="{$total_pages}" data-list-id="{$list_id}">
					<i class="icon-double-angle-right"></i>&nbsp;
				</a>
			</li>
		</ul>
		<script type="text/javascript">
			$('.pagination-link').on('click',function(e){
				e.preventDefault();

				if (!$(this).parent().hasClass('disabled'))
					$('#submitFilter'+$(this).data("list-id")).val($(this).data("page")).closest("form").submit();
			});
		</script>
	</div>
	{/if}
</div>
{block name="footer"}
{foreach from=$toolbar_btn item=btn key=k}
	{if $k == 'back'}
		{assign 'back_button' $btn}
		{break}
	{/if}
{/foreach}
{if isset($back_button)}
<div class="panel-footer">
	<a id="desc-{$table}-{if isset($back_button.imgclass)}{$back_button.imgclass}{else}{$k}{/if}" class="btn btn-default" {if isset($back_button.href)}href="{$back_button.href}"{/if} {if isset($back_button.target) && $back_button.target}target="_blank"{/if}{if isset($back_button.js) && $back_button.js}onclick="{$back_button.js}"{/if}>
		<i class="process-icon-back {if isset($back_button.class)}{$back_button.class}{/if}" ></i> <span {if isset($back_button.force_desc) && $back_button.force_desc == true } class="locked" {/if}>{$back_button.desc}</span>
	</a>
</div>
{/if}
{/block}
</div>
{if !$simple_header}
		<input type="hidden" name="token" value="{$token}" />
{/if}
</div>
{block name="endForm"}
</form>
{/block}
{block name="after"}{/block}
