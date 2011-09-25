#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby

require 'rubygems'
require 'mysql'
require 'builder'
require 'feed-normalizer'
require 'ap'
require 'pp'

require 'rss/maker'

require 'IO.rb'

rssFeedsQuery = "Select rss_name, cat_id, feed_type, rss_url, id, agencies.agency_id, agency_name, pattern, aux_url, matches, period From feed_cats Left Join rss_feeds On rss_feeds.id=feed_cats.feed_id Left Join agencies On agencies.agency_id=rss_feeds.agency_id Where status=1 And feed_type=1"
#getFeedPagesQuery = "select page_id, page_name, page_xml from pages where page_id > 0"
#getRssFeedsByPage = "select rc.rss_id from page_categories pc inner join rss_categories rc on pc.cat_id = rc.cat_id where pc.page_id="

rssFeeds = fetchAll(@db.query(rssFeedsQuery))

rssFeeds.each do |feed|
  ap feed
end 

#result = fetchAll(@db.query(getFeedPagesQuery))

#result.each do |res|
#  query = "Select * From news2 Where rss_id in (#{getRssFeedsByPage}#{res['page_id']}) Order by news2.id DESC Limit 20"

#  thisResult = fetchAll(@db.query(query))

#  thisResult.each do |thisres|
#    ap thisres
#  end

##ap query;

#end

#items = [{
#  "title" => "Egymilliárd dollárra perli a Google-t az Oracle - Totalbike.hu",
#  "link"  => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109",
#  "description" => "Az Android szabadalomsértései miatt érte kár a céget a vád szerint. Eredetileg hatmilliárdot mondtak, de a bíróság ezt elutasította.",
#  "guid" => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109"
#},{
#  "title" => "Egymilliárd dollárra perli a Google-t az Oracle - Totalbike.hu",
#  "link"  => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109",
#  "description" => "Az Android szabadalomsértései miatt érte kár a céget a vád szerint. Eredetileg hatmilliárdot mondtak, de a bíróság ezt elutasította.",
#  "guid" => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109"
#}]

#content = rssWrite(items, "auto")
#puts content
#writeFile("test.xml",content)


#puts  time.public_methods #"#{time.wday},"







