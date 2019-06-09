
{if isset($faq_categories) && !empty($faq_categories)}
		<div id="faqs" class="inixframe">
			{foreach from=$faq_categories item=faqcategories}			
            	{assign var='curr_faq_category_id' value=$faqcategories->id}
                <h2>{$faqcategories->name|escape:'htmlall':'UTF-8'}</h2>

                <p>{$faqcategories->description|escape:'htmlall':'UTF-8'}</p>
            	{if isset($faq_pages1.$curr_faq_category_id) && !empty($faq_pages1.$curr_faq_category_id) }
                    <div class="panel-group" id="accordion">
                        {foreach from=$faq_pages1.$curr_faq_category_id item=faqpages}
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#answer{$faqpages.id_faq}">
                                        {$faqpages.question|escape:'htmlall':'UTF-8'}
                                    </a>
                                </h4>
                            </div>
                            <div  name="answer{$faqpages.id_faq}" id="answer{$faqpages.id_faq}" class="panel-collapse collapse">
                                <div class="panel-body">
                                    {if version_compare($smarty.const._PS_VERSION_,'1.7.0.0','<')}
                                        {$faqpages.answer}
                                        {else}
                                        {$faqpages.answer nofilter}
                                    {/if}
                                </div>
                            </div>
                        </div>
                        {/foreach}
                    </div>
                {/if}
			{/foreach}
		</div>
{/if}

<script>
    $(window).load(function(){
        $("#accordion").find(".panel-heading").css({ cursor: 'pointer' }).on('click',function(e){
            e.preventDefault();
            var target  = $(this).find('a').attr('href');
            $(target).collapse('toggle');
        });
    });
</script>