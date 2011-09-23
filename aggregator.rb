#!/home/vvv/.rvm/rubies/ruby-1.8.7-p334/bin/ruby
require 'rubygems'
require 'feed-normalizer'

#Define URL and Parse Feed
feed_url = 'http://index.hu/24ora/rss/'
rss = FeedNormalizer::FeedNormalizer.parse open(feed_url)

#Quit if no articles
exit unless rss.entries.length > 0

#Read entries
rss.entries.each do |entry|
	title = entry.title
	body = entry.content
	authors = entry.authors.join(', ') rescue ''
	entry_url = entry.urls.first


  #puts "#{title}\n"
	#Your Logic Here
end

#puts rss.entries[0].inspect


#rss.entries[0].each do |k,v|
  #puts "#{k}\n"
#end

