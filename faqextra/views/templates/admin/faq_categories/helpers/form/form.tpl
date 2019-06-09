{*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="{$frame_local_path}template/helpers/form/form.tpl"}
{block name="input"}
	{if $input.name == "link_rewrite"}
		<script type="text/javascript">
		{if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
			var PS_ALLOW_ACCENTED_CHARS_URL = 1;
		{else}
			var PS_ALLOW_ACCENTED_CHARS_URL = 0;
		{/if}
		</script>
		{$smarty.block.parent}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name="leadin"}
	<div class="warn draft" style="{if $active}display:none{/if}">
		<p>
		<span style="float: left">
		{l s='Your Category page will be saved as a draft' mod='faqextra'}
		</span>
		<br class="clear" />
		</p>
	</div>
{/block}

{block name="input"}
    {if $input.name == "link_rewrite"}
        <script type="text/javascript">
            {if isset($PS_ALLOW_ACCENTED_CHARS_URL) && $PS_ALLOW_ACCENTED_CHARS_URL}
            var PS_ALLOW_ACCENTED_CHARS_URL = 1;
            {else}
            var PS_ALLOW_ACCENTED_CHARS_URL = 0;
            {/if}
        </script>
    {$smarty.block.parent}
    {elseif $input.type == 'category_box'}
            {$input.category_tree}
	{elseif $input.type == 'select_category'}
		<select name="{$input.name}">
			{$input.options.html}
		</select>
	{elseif $input.type == 'hidden_category'}
		    {$input.options.html}
    {elseif $input.type == 'cms_pages'}

        <div class="note note-warning">{l s='You should place the shortcode' mod='faqextra'} <b>[faq:&lt;id_faq_category&gt;]</b> {l s='in your CMS page content. You have to replace' mod='faqextra'} &lt;id_faq_category&gt; {l s='with the ID of the category you wish to dislpay' mod='faqextra'}</div>

    {else}
		{$smarty.block.parent}
	{/if}
{/block}

