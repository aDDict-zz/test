#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby

require 'rubygems'
require 'mysql'
require 'builder'
require 'feed-normalizer'
require 'ap'

require 'rss/maker'

require 'IO.rb'

getFeedPagesQuery = "select page_id, page_name, page_xml from pages where page_id > 0"
getRssFeedsByPage = "select rc.rss_id from page_categories pc inner join rss_categories rc on pc.cat_id = rc.cat_id where pc.page_id="



#result = fetchAll(@db.query(getFeedPagesQuery))

#result.each do |res|
#  query = "Select * From news2 Where rss_id in (#{getRssFeedsByPage}#{res['page_id']}) Order by news2.id DESC Limit 20"

#ap query;

#end

items = [{
  "title" => "Egymilliárd dollárra perli a Google-t az Oracle - Totalbike.hu",
  "link"  => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109",
  "description" => "Az Android szabadalomsértései miatt érte kár a céget a vád szerint. Eredetileg hatmilliárdot mondtak, de a bíróság ezt elutasította.",
  "guid" => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109"
},{
  "title" => "Egymilliárd dollárra perli a Google-t az Oracle - Totalbike.hu",
  "link"  => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109",
  "description" => "Az Android szabadalomsértései miatt érte kár a céget a vád szerint. Eredetileg hatmilliárdot mondtak, de a bíróság ezt elutasította.",
  "guid" => "http://www.hirek.hu/?from=auto.xml&amp;page_id=3&amp;news_id=3280109"
}]

ff = rssWrite(items, "auto")

puts ff


#puts  time.public_methods #"#{time.wday},"







