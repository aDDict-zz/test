

#@params = {
#  :host => '192.168.1.102',
#  :user => 'wiw_gen',
#  :pssw => 'foci06vb',
#  :db   => 'hirek_old'
#}

#@params = {
#  :host => 'localhost',
#  :user => 'root',
#  :pssw => 'v',
#  :db   => 'hirekhu'
#}

@params = {
  :host => 'localhost',
  :user => 'root',
  :pssw => 'v',
  :db   => 'hirek'
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
  
  @title = title
  
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

  rss = RSS::Maker.make("2.0") do |maker|
    maker.channel.about       = "http://example.com/index.rdf"
    maker.channel.title       = type[@title]
    maker.channel.description = "Hirek.hu #{time.year}"
    maker.channel.link        = "http://www.hirek.hu/rss.php?page=#{title}"

  items.each do |thisItem|
    maker.items.new_item do |item|
      item.link         = thisItem["link"]
      item.title        = thisItem["title"]
      item.description  = thisItem["description"]
    end
  end
  end
  rss
end

def writeFile(file, content)
  File.open(file,"w") do |f|
    f.write(content)
  end
end
