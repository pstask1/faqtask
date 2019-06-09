{if version_compare($smarty.const._PS_VERSION_,'1.6.0.0','<')}
    {include file="$tpl_dir./breadcrumb.tpl"}
{/if}
<div class="inixframe">
    {if isset($search_result) && empty($search_result)}
        <p class="note note-warning">
            {l s='No results were found for your search' mod='faqextra'}{if isset($query) && !empty($query)}&nbsp;"{$query|escape:'htmlall':'UTF-8'}"{/if}
        </p>
    {/if}
    <form action="" method="post" class="form">
        <div class="form-group col-lg-6">
            <select name="search_category" id="faq_control" class="form-control faq-control"
                    onchange="location.href=$(this).val()">
                <option value="{$link->getFAQCategoryLink(1)}">{l s='All categories' mod='faqextra'}</option>
                {foreach from=$html_categories item=htmlcategories}
                    {$htmlcategories}
                {/foreach}
            </select>
        </div>
        <div class="input-group col-lg-6">
            <input id="query" class="form-control" type="text" value="{$query|escape:'htmlall':'UTF-8'}" name="query"
                   placeholder="{l s='Search ...' mod='faqextra'}">
            <span class="input-group-btn">
                <button id="submitSearch" class="btn btn-default" type="submit" name="submitSearch" style="height: 34px"><i
                        class="icon-search"></i> {l s='Search' mod='faqextra'}</button>
            </span>
        </div>
    </form>
    {if isset($search_result) && !empty($search_result)}
        <p class="title_block">{l s='Results for your search' mod='faqextra'}
            :{if isset($query) && !empty($query)}&nbsp;"{$query|escape:'htmlall':'UTF-8'}"{/if}</p>
        <div class="panel-group" id="accordion">
            {foreach from=$search_result item=faqpages}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#answer{$faqpages.id_faq}">
                                {$faqpages.question|escape:'htmlall':'UTF-8'}
                            </a>
                        </h4>
                    </div>
                    <div name="answer{$faqpages.id_faq}" id="answer{$faqpages.id_faq}" class="panel-collapse collapse">
                        <div class="panel-body">
                            {$faqpages.answer}
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {elseif !isset($search_result)}
        {if isset($faq_category)}
            <div class="block-faq">
                <h1>{$faq_category->name|escape:'htmlall':'UTF-8'}</h1>
                {if isset($faq_category->description) && !empty($faq_category->description)}
                    <p class="faq_description">{$faq_category->description|escape:'htmlall':'UTF-8'}</p>
                {/if}
                {if isset($sub_category) && !empty($sub_category)}
                    <div class="faq_sub">
                        <p class="title_block">{l s='Subcategories' mod='faqextra'}</p>
                        <ul class="list-group col-lg-12">
                            {foreach from=$sub_category item=subcategory}
                                <li>
                                    <a href="{$link->getFAQCategoryLink($subcategory.id_faq_category, $subcategory.link_rewrite)|escape:'htmlall':'UTF-8'}"
                                       class="list-group-item">{$subcategory.name|escape:'htmlall':'UTF-8'}</a>
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
                {if isset($faq_pages) && !empty($faq_pages)}
                    <div class="panel-group col-lg-12" id="accordion">
                        {foreach from=$faq_pages item=faqpages}
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion"
                                           href="#answer{$faqpages.id_faq}">
                                            {$faqpages.question|escape:'htmlall':'UTF-8'}
                                        </a>
                                    </h4>
                                </div>
                                <div id="answer{$faqpages.id_faq}" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        {$faqpages.answer}
                                    </div>
                                </div>
                            </div>
                        {/foreach}
                    </div>
                {/if}
            </div>
        {else}
            <div class="error">
                {l s='This page does not exist.' mod='faqextra'}
            </div>
        {/if}
        <br/>
    {/if}
    <script type="text/javascript">
        $(window).load(function () {
            if ($.uniform) {
                $.uniform.restore("#faq_control, .faq-control");
                $('#faq_control, .faq-control').parent().addClass('uniform-reset');
            }

            $("#accordion").find(".panel-heading").css('cursor' , 'pointer');

            $("#accordion").find(".panel-heading").on('click', function (e) {
                e.preventDefault();
                var target = $(this).find('a').attr('href');

                $(target).collapse('toggle');
            });
        });
    </script>
</div>
