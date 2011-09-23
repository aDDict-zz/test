

#@params = {
#  :host => '192.168.1.102',
#  :user => 'wiw_gen',
#  :pssw => 'foci06vb',
#  :db   => 'hirek_old'
#}

@params = {
  :host => 'localhost',
  :user => 'root',
  :pssw => 'v',
  :db   => 'hirekhu'
}

begin
  @db = Mysql.new(@params[:host], @params[:user], @params[:pssw], @params[:db])
  rescue Mysql::Error
    puts "cant connect to mysql"
    exit                                        
end

def fetchAll(resource)
  arr = []
  resource.each_hash do |row|
    arr.push row
  end
  arr
end

def rssRead(feed)
  FeedNormalizer::FeedNormalizer.parse open(feed)
end

def rssWrite(items, title)
  
  type = {
    "auto"            => "Autó",
    "belfolg"         => "Belföld",
    "bulvar"          => "Bulvár",
    "eletmod"         => "Életmód",
    "fooldal"         => "Főoldal",
    "gazdasag"        => "Gazdaság",
    "infotech"        => "Infotech",
    "itthon"          => "Itthon",
    "kulfold"         => "Külföld",
    "oktatas-kultura" => "Oktatás - Kultúra",
    "sport"           => "Sport",
    "tudomany"        => "Tudomány"
  }
  
  time    = Time.new
  dst     = (time.dst? ? "+0200" : "+0100")
  days    = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
  months  = ["", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Okt", "Nov", "Dec"]
  timeNow = "#{days[time.wday]}, #{time.day} #{months[time.month]} #{time.year} #{time.hour}:#{time.min}:#{(time.sec.to_s.length == 1 ? '0' + time.sec.to_s : time.sec)} #{dst}"

  version = "2.0"
  content = RSS::Maker.make(version) do |m|
    m.channel.title = type[title]
    m.channel.link = "http://www.hirek.hu/rss.php?page=#{title}"
    m.channel.description = ""
    m.items.do_sort = true # sort items by date
      
    i = m.items.new_item
    i.title = "Ruby can parse RSS feeds"
    i.link = "http://www.rubyrss.com/"
    i.date = Time.parse("2007/2/11 14:01")

    i = m.items.new_item
    i.title = "Ruby can create RSS feeds"
    i.link = "http://www.rubyrss.com/"
    i.date = Time.now
  end
  puts content
end

#  xml = Builder::XmlMarkup.new( :target => "", :indent => 2 )
#  xml.instruct! :xml, :version => "1.0" #:encoding => "US-ASCII"

#  xml.rss do |rssItem|
#    rssItem.title           type[title]
#    rssItem.link            "http://www.hirek.hu/rss.php?page=#{title}"
#    rssItem.description     ""
#    rssItem.language        "hu-HU"
#    rssItem.copyright       "Hirek.hu #{time.year}" 
#    rssItem.managingEditor  "szerkeszto@hirek.hu (Szerkesztő)"
#    rssItem.webMaster       "webmester@hirek.hu (Webmester)"
#    rssItem.pubDate         timeNow
#    rssItem.lastBuildDate   timeNow
#    rssItem.category        ""
#    rssItem.generator       "Hirek.hu"
#    rssItem.docs            "http://blogs.law.harvard.edu/tech/rss"
#    rssItem.ttl             60
#    
#    items.each do |element|
#      rssItem.item do |el|
#        el.title        element["title"]
#        el.link         element["link"]
#        el.description  element["description"]
#        el.guid         element["guid"]
#      end
#    end
    
#    rssItem."atom:link"   (:href => "http://www.hirek.hu/rss.php?page=auto" rel="self" type="application/rss+xml")
         

  
#version = "2.0" # ["0.9", "1.0", "2.0"]
#destination = "test_maker.xml" # local file to write

#content = RSS::Maker.make(version) do |m|
#  m.channel.title = "Example Ruby RSS feed"
#  m.channel.link = "http://www.rubyrss.com"
#  m.channel.description = "Old news (or new olds) at Ruby RSS"
#  m.items.do_sort = true # sort items by date
#    
#  i = m.items.new_item
#  i.title = "Ruby can parse RSS feeds"
#  i.link = "http://www.rubyrss.com/"
#  i.date = Time.parse("2007/2/11 14:01")

#  i = m.items.new_item
#  i.title = "Ruby can create RSS feeds"
#  i.link = "http://www.rubyrss.com/"
#  i.date = Time.now
#end

#File.open(destination,"w") do |f|
#f.write(content)
#end
  
  
#  items.each do |item|
#    item.each do |key, value|
#      puts "#{key} is #{value}"
#    end
#    
#  end

#ap items

#<title>Autó</title>
#      <link>http://www.hirek.hu/rss.php?page=auto</link>
#      <description></description>
#      <language>hu-HU</language>
#      <copyright>Hirek.hu 2011</copyright>

#      <managingEditor>szerkeszto@hirek.hu (Szerkesztő)</managingEditor>
#      <webMaster>webmester@hirek.hu (Webmester)</webMaster>      
#      <pubDate>Fri, 23 Sep 2011 12:45:01 +0200</pubDate>
#      <lastBuildDate>Fri, 23 Sep 2011 12:45:01 +0200</lastBuildDate>
#      <category></category>
#      <generator>Hirek.hu</generator>
#      <docs>http://blogs.law.harvard.edu/tech/rss</docs>

#      <ttl>60</ttl>
#	<atom:link href="http://www.hirek.hu/rss.php?page=auto" rel="self" type="application/rss+xml" />

#  xml.rss["title"](title)
  

#  favorites = {
#    'candy' => 'Neccos', 'novel' => 'Empire of the Sun', 'holiday' => 'Easter'
#  }

#  xml = Builder::XmlMarkup.new( :target => "", :indent => 2 )

#  xml.instruct! :xml, :version => "1.0"

#  xml.favorites do 
#    favorites.each do | name, choice |
#      xml.favorite( choice, :item => name )
#    end
#  end

#end

#ap result

#Define URL and Parse Feed
#feed_url = 'http://index.hu/24ora/rss/'
#rss = FeedNormalizer::FeedNormalizer.parse open(feed_url)

#Quit if no articles
#exit unless rss.entries.length > 0

#Read entries
#rss.entries.each do |entry|
#	title = entry.title
#	body = entry.content
#	authors = entry.authors.join(', ') rescue ''
#	entry_url = entry.urls.first


#  ap "#{title}\n"
	#Your Logic Here
#end

#ap rss.entries[0].inspect
