{if isset($faq_pages) && !empty($faq_pages)}
    <!-- Block mymodule -->
    <div id="faqextra_block_left {if $FAQ_DISPLAY}slide{/if}" class="block informations_block_left">
        <div class="block_content">
            <p class="title_block faq-title">
                <a href="{$faq_module_link|escape:'htmlall':'UTF-8'}">{$faq_module_name|escape:'htmlall':'UTF-8'}</a>
            </p>
            {if !$FAQ_DISPLAY}
                <div class="list-group faq-list" {if version_compare($smarty.const._PS_VERSION_ , '1.7.0' , '>=')}style="margin-bottom:10px"{/if} >
                    {foreach from=$faq_pages item=faqpages}
                        <a class="list-group-item faq-list-item" href="{$faq_module_link}#qa{$faqpages.id_faq}" title="{$faqpages.question|escape:'htmlall':'UTF-8'}">
                            <i class="fa fa-question-circle fa-fw" aria-hidden="true"></i>
                            {$faqpages.question|escape:'htmlall':'UTF-8'}
                        </a>
                        <p>{$faqpages.answer}</p>
                    {/foreach}
                </div>
            {else}
                <div class="slider-faq">
                    {foreach from=$faq_pages item=faqpages}
                        <div class="slide">
                            <span class="textonly">
                                <h3 class="light question-slider"><a href="{$faq_module_link}#qa{$faqpages.id_faq}" title="{$faqpages.question|escape:'htmlall':'UTF-8'}">{$faqpages.question|escape:'htmlall':'UTF-8'}</a></h3>
                                <p class="h-divider"></p>
                                <h6 class="light answer-slider">{$faqpages.answer nofilter}</h6>
                            </span>
                        </div>
                    {/foreach}
                </div>
            {/if}
        </div>
    </div>

    <!-- /Block mymodule -->
{/if}