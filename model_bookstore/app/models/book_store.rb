class BookStore # < ActiveRecord::Base
  def self.search_by_bookStore(params)
    if params[:bookStores] != "" && Schema.class_exists?(params[:bookStores])
      @resArray = []
      @domains = {
        "Amazon" => "http://www.amazon.com"
      }
      @Request = eval("#{params[:bookStores]}Request").new(params)
      @doc = @Request.result #; return @doc;
      if @doc != -1
        @Schema = eval(params[:bookStores]).new(@doc)
        @linx = []
        @linx = @Schema.getLinxToTheProducts;
        @linx.each do |link|
          # van ahol csak relativan van megadva az url( valojaban eloszor rossz linkeket is kovettem, de vegul benne hagytam, who knows )
          !Regexp.new(/#{@domains[params[:bookStores]]}.*/).match(link) ? link = "#{@domains[params[:bookStores]]}#{link}" : ""
          @Schema = eval(params[:bookStores]).new(open(link))
          @resArray.push({
            "poduct" => link,
            "img" => @Schema.getImgUrl,
            "price" => @Schema.getPrice,
            "title" => @Schema.getTitle,
            "author" => @Schema.getAuthor,
            "description" => @Schema.getDescription,
            "ISBN" => @Schema.getISBN,
            "publisher" => @Schema.getPublisher,
            "reviews" => @Schema.reviews
          })
        end
      else
        -1
      end
      @resArray
    else
      -2
    end
  end
end

# ezt nem ide kellene irni, hanem melyebbre az environmentben, de a jobb attekinthetoseg kedveert ide kerult
# kiterjeszti a String osztalyt
class String
  def removeHtmlGarbage
    self.gsub(/(<[^<>]+>)/, "").fixBadlatinOne.escapeHtmlEntities.strip
  end

#  def removeSingleTags
#    self.gsub(/(<[^<>]+\/>)/, "")
#  end

  def removeHtmlContent(element)
    self.gsub(/(<#{element}.*#{element}>)/, "")
  end
  # Hpricot bug - img.attributes["src"] nem mukodik
  def getImageSource
    self.split('src="')[1].to_s.split('" ')[0]
  end

  def escapeHtmlEntities
    HTMLEntities.new.decode(self)
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
      sstr = str.gsub(/(#{k})/, v)
      str = sstr
    end
    str
  end
end

class HttpartyRequest

  include HTTParty
  attr_accessor :headers, :body, :query, :urlPart, :result, :method

  def initialize(pmeters)
    @headers = getHeaders
    @body = getBody
    @query = getQuery(pmeters)
    @urlPart = getUrlPart
    @result = getFullContent(pmeters)
  end

  def getHeaders
    {}
  end

  def getBody
  end

  def getQuery(pmeters)
  end

  def getUrlPart
  end

  def getFullContent(pmeters)
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

  def getUrlPart
    "/search/advanced/advancedBookSearch.action"
  end

  def getQuery(pmeters)
    { "keywords" => pmeters[:title],
      "author" => pmeters[:author],
      "keywordOption" => "AND",
      "includeSimilarKeywords" => 1,
      "includeSimilarAuthors" => 1,
      "includeAntique" => 1 }
  end
end

class AmazonRequest < HttpartyRequest

  base_uri 'www.amazon.com'

  def getUrlPart
    "/gp/search/ref=sr_adv_b/"
  end

  def getQuery(pmeters)
    { "field-keywords" => "#{pmeters[:title]}, #{pmeters[:author]}",
      "field-title" => pmeters[:title],
      "field-author" => pmeters[:author],
      "search-alias" => "stripbooks" }
  end

  def getFullContent(pmeters)
    content = Hpricot(get)
    head = content.search("//head").to_s
    # ha szerepel pager az oldalon, akkor a brutto resultsetet tobb requesttel kell elkerni
    pager = content.search("//td[@class='pagn']")
    if pager.size > 0 && content.search("//h1[@id='noResultsTitle']").size == 0
      total = getTotal(content)["total"]

      #  a talalatok maximalasa, ad hoc
      total > 10 ? total = 0 : total

      currentRes = getTotal(content)["current"]
      currentPage = 2
      content = content.search("//body").inner_html
      while currentRes <= total do
        @query["page"] = currentPage
        result = Hpricot(get)
        currentRes = getTotal(result)["current"]
        content += result.search("//body").inner_html
        currentPage += 1
      end
      "<html>#{head}<body>#{content}</body></html>"
    else
      -1
    end
  end

  def getTotal(content)
    res = content.search("//td[@class='resultCount']").inner_html
    arr = res.split("of")
    arr[2] = arr[0].split("-")
    arr[3] = arr[1].split("Results")
    { "total" => arr[3][0].gsub(/(\,)/, "").strip.to_i, "current" => arr[2][1].gsub(/(\,)/, "").strip.to_i }
  end
end

