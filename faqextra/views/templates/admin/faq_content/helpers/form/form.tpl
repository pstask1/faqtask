{extends file="{$frame_local_path}template/helpers/form/form.tpl"}

{block name="input"}
	{if $input.type == 'faq_categories'}
		<div class="col-lg-12">
		{$input.html}
		</div>
		{$smarty.block.parent}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
							