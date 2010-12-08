class BookStore < ActiveRecord::Base
  def self.search_by_bookStore(params)
    if params[:bookStores] != "" && Schema.class_exists?(params[:bookStores])
      @domains = {
        "Amazon" => "http://www.amazon.com"
      }
      @Request = eval("#{params[:bookStores]}Request").new(params)
      @doc = @Request.result
      @linx = []
      @linx = eval(params[:bookStores]).new(@doc).getLinxToTheProducts
      i = 0
      @linx.each do |link|
        # van ahol csak relativan van megadva az url( valojaban eloszor rossz linkeket is kovettem, de vegul benne hagytam, who knows )
        !Regexp.new(/#{@domains[params[:bookStores]]}.*/).match(link) ? link = "#{@domains[params[:bookStores]]}#{link}" : ""
        if i == 1
          @Schema = eval(params[:bookStores]).new(open(link))
        end
        i += 1
      end
      @Schema.getImgUrl
    else
      "Something went wrong<br /><a href='/'>Back</a>"
    end
  end
end

# ezt nem ide kellene irni, hanem melyebbre az environmentben, de a jobb attekinthetoseg kedveert ide kerult
# kiterjeszti a String osztalyt
class String
  def removeHtmlGarbage
    self.gsub(/(<[^<>]+>)/, "")
  end

  def removeHtmlContent(element)
    self.gsub(/(<#{element}.*#{element}>)/, "")
  end
  # Hpricot bug - img.attributes["src"] nem mukodik
  def getImageSource
    self.split('src="')[1].to_s.split('" ')[0]
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
    # ha szerepel pager az oldalon, akkor a brutto resultsetet tobb requesttel kell elkerni
    pager = content.search("//td[@class='pagn']")
    if pager.size > 0 && content.search("//h1[@id='noResultsTitle']").size == 0
      total = getTotal(content)["total"]

      #  a talalatok maximalasa, ad hoc
      total > 200 ? total = 14 : total

      currentRes = getTotal(content)["current"]
      currentPage = 2
      content = content.search("//body").to_s
      while currentRes <= total do
        @query["page"] = currentPage
        result = Hpricot(get).search("//body")
        currentRes = getTotal(result)["current"]
        content += result.to_s
        currentPage += 1
      end
      content
    else
      content
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

