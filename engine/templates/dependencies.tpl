{foreach from=$var key=key item=item}
    {if $item == "css"}
        <link rel="stylesheet" type="text/css" href="/css/{$key}.css" media="all" />
    {elseif $item == "js"}
        <script type="text/javascript" src="/js/{$key}.js"></script>
	{elseif $item == "tmc"}
        <script type="text/javascript" src="/js/tinymce/{$key}.js"></script>
    {/if}
{/foreach}