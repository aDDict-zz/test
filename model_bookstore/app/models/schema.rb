class Schema

  attr_accessor :xpaths, :doc, :linkToTheProduct, :img, :price, :title, :author, :description, :ISBN, :publisher, :reviews

  def initialize(doc)
    @xpaths = loadYaml
    @doc = createHpricotInstance(doc)
    @linkToTheProduct = getLinxToTheProducts
    @img = getImgUrl
    @price = getPrice
    @title = getTitle
    @author = getAuthor
    @description = getDescription
    @ISBN = getISBN
    @publisher = getPublisher
    @reviews = getReviews
  end

  def loadYaml
    hash = File.open("config/xpaths.yml") do |y| YAML::load(y) end
    hash[self.class.to_s]
  end

  def createHpricotInstance(doc)
    Hpricot(doc)
  end

  def getLinxToTheProducts
  end

  def getImgUrl
  end

  def getPrice
  end

  def getTitle
  end

  def getAuthor
  end

  def getDescription
  end

  def self.getLinxToTheProducts
  end

  def getISBN
    {}
  end

  def getPublisher
  end

  def getReviews
    {}
  end

  def self.class_exists?(name)
    begin
      true if Kernel.const_get(name)
    rescue NameError
      false
    end
  end

end

class Amazon < Schema

  def getLinxToTheProducts
    path = @xpaths["linkToTheProduct"]
    elements = []
    @doc.search(@xpaths["linkToTheProduct"]).each do |a|
      elements.push(Hpricot(a.to_s).search("//a")[0].attributes["href"])
    end
    elements
  end

  def getImgUrl
    @doc.at(@xpaths["img"]).to_s.getImageSource
  end

  def getPrice
    @doc.at(@xpaths["price"]).to_s.removeHtmlGarbage
  end

  def getTitle
    @doc.search(@xpaths["title"]).inner_html.removeHtmlContent("span").removeHtmlGarbage
  end

  def getAuthor
    @doc.search(@xpaths["author"])[0].to_s.removeHtmlGarbage
  end

  def getDescription
    str = ""
    @doc.search(@xpaths["description"]).each do |description|
      str += description.to_s.removeHtmlGarbage
    end
    str
  end

  def getPublisher
    str = ""
    @doc.search(@xpaths["publisher"]).each do |publisher|
      if Regexp.new(/Publisher/).match(publisher.inner_html)
        str = publisher.inner_html.removeHtmlContent("b").removeHtmlGarbage
      end
    end
    str
  end

  def getISBN
    hash = {}
    @doc.search(@xpaths["ISBN"]).each do |isbn|
      if Regexp.new(/ISBN-10/).match(isbn.inner_html)
        hash["ISBN-10"] = isbn.inner_html.removeHtmlContent("b")
      end
      if Regexp.new(/ISBN-13/).match(isbn.inner_html)
        hash["ISBN-13"] = isbn.inner_html.removeHtmlContent("b")
      end
    end
    hash
  end

  def getReviews
    hash = {}
    @doc.search(@xpaths["reviews"]).each do |a|
      if Regexp.new(/customer reviews/).match(a.inner_html)
        hash = {
          "link" => a.attributes["href"],
          "content" => a.inner_html
          }
      end
    end
    hash
  end

end

