{foreach from=$var key=key item=item}
    {if $item == "content-type"}
        <meta http-equiv="{$key}" content="charset={$item}" />
    {else}
        <meta name="{$key}" content="{$item}" />
    {/if}
{/foreach}