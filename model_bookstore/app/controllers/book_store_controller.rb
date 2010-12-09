class BookStoreController < ApplicationController
  require 'hpricot'
  require 'open-uri'
  require 'htmlentities'
  require 'wikipedia'
  require 'mediacloth'
  require 'json'

  def start
  end

  def search
    render :json => BookStore.get_result_by_bookstore(params)
  end

  def searchInWiki
    render :json => BookStore.get_result_by_wiki(params)
  end

  # dev cucc
  def showpage
#    render :text => Hpricot(open('http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=dostojevski&x=12&y=22'))
#    render :text => Hpricot(open('http://www.amazon.com/Arguing-Idiots-Small-Minds-Government/dp/1416595023/ref=sr_1_1/183-6842084-4598745?ie=UTF8&s=books&qid=1291812561&sr=1-1'))
#    render :text => Hpricot(open("http://www.amazon.com/gp/search/ref=sr_adv_b/?search-alias=stripbooks&unfiltered=1&field-keywords=&field-author=&field-title=idiot&field-isbn=&field-publisher=&node=&field-p_n_condition-type=&field-feature_browse-bin=&field-binding_browse-bin=&field-subject=&field-language=&field-dateop=&field-datemod=&field-dateyear=&sort=relevanceexprank&Adv-Srch-Books-Submit.x=34&Adv-Srch-Books-Submit.y=11"))
#    render :text => Hpricot(open("http://www.amazon.com/Idiot-America-Stupidity-Became-Virtue/dp/0767926153/ref=sr_1_2?s=books&ie=UTF8&qid=1291818936&sr=1-2"))
#    render :text => Hpricot(open("http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Dstripbooks&field-keywords=dostoevsky&x=12&y=20"))
#    render :text => Hpricot(open("http://www.amazon.com/Children-H%C3%BArin-J-R-Tolkien/dp/0345518845/ref=sr_1_7/190-4818074-0564219?ie=UTF8&s=books&qid=1291989535&sr=1-7"))

#    Wikipedia.Configure {
#      format 'json'
#      domain 'en.wikipedia.org'
#      path   'w/api.php'
#    }
#    page = Wikipedia.find('dostoevsky', :prop => "info")

#    pageid = page.raw_data["query"]["pages"].sort[0][0]
#     Wikipedia.Configure {
#      format 'json'
#      domain 'en.wikipedia.org'
#      path 'w/api.php'
#      pageids pageid
#    }
#    page = Wikipedia.find('dostoevsky', :prop => "info")
#    page = Wikipedia.find('dostoevsky')
#    hash = open(page.content) do |y| YAML::load(y) end
#    result = JSON.parse(raw_data)
#    render :text => open("http://en.wikipedia.org/w/api.php?action=query&prop=revisions&rvprop=content&format=xml&titles=Main%20Page")
#    render :text => page.raw_data["query"]["pages"].sort[0][1]["lastrevid"].inspect
#    render :text => open("http://index.hu")
#    render :text => page.content
    render :text => BookStore.get_page
  end

end

