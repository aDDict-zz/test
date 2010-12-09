class Schema

  attr_accessor :xpaths, :doc, :linkToTheProduct, :img, :publicationdate, :price, :title, :author, :description, :ean, :ISBN, :publisher, :reviews, :items

  def initialize(doc)
    @xpaths = loadYaml
    @doc = setHpricotInstance(doc)
    @linkToTheProduct = setLinxToTheProducts
    @img = setImgUrl
    @price = setPrice
    @title = setTitle
    @author = setAuthor
    @description = setDescription
    @ean = setEan
    @ISBN = setISBN
    @publisher = setPublisher
    @reviews = setReviews
    @items = setItems
    @publicationdate = setPublicationdate
  end

  def loadYaml
    hash = File.open("config/xpaths.yml") do |y| YAML::load(y) end
    hash[self.class.to_s.split(/Schema/)[0]]
  end

  def setHpricotInstance(doc)
    Hpricot(doc)
  end

  def setLinxToTheProducts
  end

  def setImgUrl
  end

  def setPrice
  end

  def setTitle
  end

  def setAuthor
  end

  def setDescription
  end

  def self.setLinxToTheProducts
  end

  def setISBN
  end

  def setEan
  end

  def setPublisher
  end

  def setReviews
    {}
  end

  def setItems
    []
  end

  def setPublicationdate
  end

  def self.class_exists?(name)
    begin
      true if Kernel.const_get(name)
    rescue NameError
      false
    end
  end

end

class WikipediaSchema < Schema

  def setItems
    res = {}
    @doc.search(@xpaths["item"]).each do |item|
      if Regexp.new(/#{@xpaths["matcher"]}/).match(item.attributes["class"])
        if item.at(@xpaths["nameSpan"])
          @name = item.at(@xpaths["nameSpan"]).inner_html
        elsif item.at(@xpaths["nameTh"])
          @name = item.at(@xpaths["nameTh"]).inner_html
        end

        res = {
          "name" => @name
        }
      end
    end
    res
   end

end

class AmazonSchema < Schema

  def setItems
    res_array = []
    @doc.search(@xpaths["item"]).each do |item|
    res_array.push({
        "author" => item.search(@xpaths["author"])[0].to_s.removeHtmlGarbage,
        "title" => item.at(@xpaths["title"]).to_s.removeHtmlGarbage,
        "linkToTheProduct" => item.at(@xpaths["detailpageurl"]).inner_html,
        "img" => item.at(@xpaths["mediumimage"]).to_s.removeHtmlGarbage,
        "publicationdate" => item.at(@xpaths["publicationdate"]).to_s.removeHtmlGarbage,
        "publisher" => item.at(@xpaths["publisher"]).to_s.removeHtmlGarbage,
        "ean" => item.at(@xpaths["ean"]).to_s.removeHtmlGarbage,
        "isbn" => item.at(@xpaths["isbn"]).to_s.removeHtmlGarbage,
        "price" => item.at(@xpaths["lowestnewprice"]).to_s.gsub(/\$/, "").removeHtmlGarbage
      })
    end
    res_array
  end

end

class BooklineSchema < Schema

  def setItems
    @base_uri = "http://bookline.hu"
    res_array = []
    items = @doc.search(@xpaths["item"])
    @iter = 1
    items.each do |item|
      if Regexp.new(/#{@xpaths["matcher"]}/).match(item.to_s)
        if @iter < 11
          link = "#{@base_uri}#{item.at(@xpaths["linkToTheProduct"]).attributes["href"]}"
          doc = Hpricot(open(link))
          res_array.push({
            "author" => setOriginalNameFormat(doc.at(@xpaths["author"]).inner_html.removeGarbage.removeHtmlGarbage).removeBracketContent,
            "title" => doc.at(@xpaths["title"]).inner_html.removeHtmlGarbage,
            "linkToTheProduct" => link,
            "img" => doc.at(@xpaths["img"]).to_s.getImageSource,
            "publicationdate" => doc.at(@xpaths["publicationdate"]).inner_html.to_s.removeHtmlGarbage.split(", ")[1],
            "publisher" => doc.at(@xpaths["publicationdate"]).inner_html.removeHtmlGarbage.split(", ")[0],
            "ean" => "no data",
            "isbn" => doc.search(@xpaths["isbn"])[2] ? doc.search(@xpaths["isbn"])[2].to_s.split("ISBN: ")[1].removeHtmlGarbage.removeGarbage : "no data",
            "price" => doc.at(@xpaths["price"]).to_s.removeHtmlGarbage.split("Ft")[0]
          })
          @iter += 1
        end
      end
    end
    res_array
  end

  def setOriginalNameFormat(str)
    if Regexp.new(/,/).match(str)
    "#{str.split(",")[1].to_s.split} #{str.split(",")[0].to_s.split}"
  else
    str
  end
  end

end

