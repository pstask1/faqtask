{extends file="{$frame_local_path}template/helpers/list/list_header.tpl"}

{block name=leadin}
    <script type="text/javascript">
        var $module_name = '{$module_name}';
        var $frame_path_uri  = '{$frame_path_uri}';

        var token = '{$token}';
        var update_success_msg = '{l s='Update successful' mod='faqextra' js=1}';

    </script>

        <h3 id="category_toggle">Select FAQ <i class="icon-arrow-down"></i> </h3>
        <div class="col-lg-6" id="categories">
            <form action="" method="get" id="category_form">
            <input type="hidden" name="controller" value="AdminFaqContent" />
            <input type="hidden" name="token" value="{$token}" />

                {$categories}


            </form>
        </div>


	
	<script type="text/javascript">
		{if isset($smarty.get.id_category)}
		    $("#categories").hide();
        {else}
             $('#category_toggle i').toggleClass('icon-arrow-up').toggleClass('icon-arrow-down');
		{/if}
		$("#category_toggle").click(function(){
			$("#categories").toggle(500);
            $('#category_toggle i').toggleClass('icon-arrow-up').toggleClass('icon-arrow-down');
		}).css('cursor','pointer');
		$("input[name=id_faq_category]").click(function(){
			$("#category_form").trigger('submit');
		});
	</script>
{/block}