class BookStore # < ActiveRecord::Base

  def self.get_result_by_bookstore(params)
    if params[:bookStores] != "" && Schema.class_exists?("#{params[:bookStores]}Request")
      @doc = eval("#{params[:bookStores]}Request").new(params).result
      if Schema.class_exists?("#{params[:bookStores]}Schema")
        eval("#{params[:bookStores]}Schema").new(@doc).items
      end
    end
  end

  def self.get_result_by_wiki(params)
    @doc = WikipediaRequest.new(params).result
    Schema::WikipediaSchema.new(@doc).items
  end

  # dev stuff
  def self.get_page
    WikipediaRequest.new({:key => "Fyodor Dostoevsky"}).result
  end

end



# kiterjeszti a String osztalyt
class String
  def removeHtmlGarbage
    self.removeNBSP.fixBadlatinOne.escapeHtmlEntities.gsub(/(<[^<>]+>)/, "").strip
  end

  def removeHtmlContent(element)
    self.gsub(/(<#{element}.*#{element}>)/, "")
  end

  def removeGarbage
    str = self.to_s
    garbage = [":"]
    garbage.each do |item|
      str = str.gsub(/#{item}/, "")
    end
    str = str.gsub(/\n/, " ")
    str = str.gsub(/\r\n/, " ")
    str
  end

  def removeBracketContent
    str = self.to_s
    if(Regexp.new(/(\()/).match(str))
      str = str.gsub(/(\(.*\))/, "")
    end
  str
  end

  # Hpricot bug - img.attributes["src"] nem mukodik
  def getImageSource
    self.split('src="')[1].to_s.split('" ')[0]
  end

  def escapeHtmlEntities
    str = self.to_s
    str = HTMLEntities.new.decode(self)
    str
  end

  def removeNBSP
    str = self.to_s
    str = str.gsub(/&nbsp;/, "")
    str
  end

  def fixBadlatinOne
    str = self.to_s
    entities = {
    '&#x80;'=>'&#x20AC;', '&#x81;'=>'?',        '&#x82;'=>'&#x201A;', '&#x83;'=>'&#x0192;',
    '&#x84;'=>'&#x201E;', '&#x85;'=>'&#x2026;', '&#x86;'=>'&#x2020;', '&#x87;'=>'&#x2021;',
    '&#x88;'=>'&#x02C6;', '&#x89;'=>'&#x2030;', '&#x8A;'=>'&#x0160;', '&#x8B;'=>'&#x2039;',
    '&#x8C;'=>'&#x0152;', '&#x8D;'=>'?',        '&#x8E;'=>'&#x017D;', '&#x8F;'=>'?',
    '&#x90;'=>'?',        '&#x91;'=>'&#x2018;', '&#x92;'=>'&#x2019;', '&#x93;'=>'&#x201C;',
    '&#x94;'=>'&#x201D;', '&#x95;'=>'&#x2022;', '&#x96;'=>'&#x2013;', '&#x97;'=>'&#x2014;',
    '&#x98;'=>'&#x02DC;', '&#x99;'=>'&#x2122;', '&#x9A;'=>'&#x0161;', '&#x9B;'=>'&#x203A;',
    '&#x9C;'=>'&#x0153;', '&#x9D;'=>'?',        '&#x9E;'=>'&#x017E;', '&#x9F;'=>'&#x0178;'
    }
    entities.each do |k, v|
      str = str.gsub(/(#{k})/, v)
    end
    str
  end
end

class HttpartyRequest

  include HTTParty
  attr_accessor :headers, :body, :query, :urlPart, :result, :method, :xpaths

  def initialize(pmeters)
    @xpaths = loadYaml
    @pmeters = pmeters
    @headers = setHeaders
    @body = setBody
    @query = setQuery
    @urlPart = setUrlPart
    @result = setResult
  end

  def loadYaml
    hash = File.open("config/xpaths.yml") do |y| YAML::load(y) end
    hash[self.class.to_s.split(/Request/)[0]]
  end

  def setHeaders
    {}
  end

  def setBody
  end

  def setQuery
  end

  def setUrlPart
  end

  def setResult
  end

  def post
    self.class.post(@urlPart, {:body => @body, :headers => @headers, :query => @query})
  end

  def get
    self.class.get(@urlPart, {:body => @body, :headers => @headers, :query => @query})
  end
end

class BooklineRequest < HttpartyRequest

  base_uri 'bookline.hu'

  def setUrlPart
    "/search/advanced/advancedBookSearch.action"
  end

  def setQuery
    { "keywords" => "#{@pmeters[:title]}, #{@pmeters[:author]}",
      "keywordOption" => "AND",
      "includeSimilarKeywords" => 1,
      "includeSimilarAuthors" => 1,
      "includeAntique" => 1 }
  end

  def setResult
    post.to_s
  end
end

class WikipediaRequest < HttpartyRequest

  base_uri 'en.wikipedia.org'

  def setUrlPart
    "/w/index.php"
  end

  def setHeaders
    { "User-Agent" => "Ruby/#{RUBY_VERSION}" }
  end

  def setQuery
    { "title" => "Special:Search",
      "search" => @pmeters[:key] }
  end

  def setResult
    post.to_s
  end

end

class AmazonRequest < HttpartyRequest

  def setUrlPart
    require "amazon/ecs"
    @Access_Key_ID = "AKIAIKSIYXGW7I35DFBQ"
    @Secret_Access_Key = "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"
    @AWS_Account_ID = "0016-9425-7664"
    @opts = {
      :aWS_access_key_id => @Access_Key_ID,
      :aWS_secret_key => @Secret_Access_Key,
      :operation => 'ItemSearch',
      :ResponseGroup => 'Medium',
      :search_index => 'Books',
      :keywords => "#{@pmeters[:title]}, #{@pmeters[:author]}",
      :timestamp => Time.now.utc.strftime("%Y-%m-%dT%H:%M:%SZ")
    }
    Amazon::Ecs.prepare_url(@opts)
  end

  def setResult
    get.body.to_s
  end

end

