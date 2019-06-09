{extends file="{$frame_local_path}template/helpers/list/list_header.tpl"}

{block name=leadin}
<div class="well well-sm">
	{assign var=i value=0}
	{foreach from=$categories_tree key=key item=category}
		{if $i++ == 0}
			&nbsp;<i class="icon-home"></i>
			{assign var=params_url value=""}
		{else}
			{assign var=params_url value="&id_faq_category={$category.id_faq_category}&viewfaq_category"}
		{/if}
		
		{if $key == 0}
			{$category.name}
		{else}
			<a href="{$currentIndex}{$params_url}&token={$token}">{$category.name}</a>&nbsp;>&nbsp;
		{/if}
	{/foreach}
</div>
{/block}