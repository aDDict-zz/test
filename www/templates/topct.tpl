<?xml version="1.0" encoding="utf-8" ?>
<rss version="0.92" >
    <channel>
    <title>Leglátogatottabb cikkek</title>
    <link>http://www.hirek.hu/</link>
    <description>hirek.hu</description>
    <language>hu</language>
    {foreach from=$ITEMS item=item}
        <item>
            <title><![CDATA[{if $item.ptitle}{$item.ptitle} - {/if}{$item.title}]]></title>
            {if $item.link}<link><![CDATA[{$item.link}]]></link>{/if}
            <description>
                {if $item.image}&lt;img src=&quot;{$item.image}&quot; border=&quot;0&quot; /&gt;{/if}
                {if $item.category}&lt;p&gt;&lt;em&gt;<![CDATA[{$item.category}]]>&lt;/em&gt;&lt;/p&gt;{/if}
                {if $item.datum}&lt;p&gt;{$item.datum}&lt;/p&gt;{/if}
                {if $item.description}&lt;p&gt;<![CDATA[{$item.description}]]>&lt;/p&gt;{/if}
                {if $item.author}&lt;p&gt;&lt;em&gt;Photos: {$item.author}&lt;/em&gt;&lt;/p&gt;{/if}
                {if $item.author_text}&lt;p&gt;&lt;em&gt;Info: {$item.author_text}&lt;/em&gt;&lt;/p&gt;{/if}
            </description>
        </item>
    {/foreach}
    </channel>
</rss>
