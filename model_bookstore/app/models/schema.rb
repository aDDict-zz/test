class Schema

  attr_accessor :doc, :linkToTheProduct, :img, :price, :title, :author

  def initialize(doc)
    @doc = createHpricotInstance(doc)
    @linkToTheProduct = getLinxToTheProducts
    @img = getImgUrl
    @price = getPrice
    @title = getTitle
    @author = getAuthor
    @xpaths = loadYaml
  end

  def loadYaml
    File.open("config/xpaths.yml") do |y| YAML::load(y) end
  end

  def createHpricotInstance(doc)
    Hpricot(doc)
  end

  def getLinxToTheProducts(doc)
  end

  def getImgUrl
  end

  def getPrice
  end

  def getTitle
  end

  def getAuthor
  end

  def self.getLinxToTheProducts
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
    path = "//td[@class='dataColumn']"
    elements = []
    @doc.search(path).each do |a|
      elements.push(Hpricot(a.to_s).search("//a")[0].attributes["href"])
    end
    elements
  end

  def getImgUrl
    path = "//img[@id='prodImage']"
    @doc.at(path).to_s.getImageSource
  end

  def getPrice
    path = "//b[@class='priceLarge']"
    @doc.at(path).to_s.removeHtmlGarbage
  end

  def getTitle
    path = "//span[@id='btAsinTitle']"
    @doc.search(path).inner_html.removeHtmlContent("span")
  end

  def getAuthor
    path = "//div[@class='buying']//span//a"
    @doc.search(path)[0].to_s.removeHtmlGarbage
  end

end

