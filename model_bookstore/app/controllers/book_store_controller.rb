class BookStoreController < ApplicationController
  require 'hpricot'
  require 'open-uri'

  def start
  end

  def search
    render :text => BookStore.search_by_bookStore(params)
  end

  def showpage
#    render :text => Hpricot(open('http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=dostojevski&x=12&y=22'))
#    render :text => Hpricot(open('http://www.amazon.com/Arguing-Idiots-Small-Minds-Government/dp/1416595023/ref=sr_1_1/183-6842084-4598745?ie=UTF8&s=books&qid=1291812561&sr=1-1'))
#    render :text => Hpricot(open("http://www.amazon.com/gp/search/ref=sr_adv_b/?search-alias=stripbooks&unfiltered=1&field-keywords=&field-author=&field-title=idiot&field-isbn=&field-publisher=&node=&field-p_n_condition-type=&field-feature_browse-bin=&field-binding_browse-bin=&field-subject=&field-language=&field-dateop=&field-datemod=&field-dateyear=&sort=relevanceexprank&Adv-Srch-Books-Submit.x=34&Adv-Srch-Books-Submit.y=11"))
    render :text => Hpricot(open("http://www.amazon.com/Idiot-America-Stupidity-Became-Virtue/dp/0767926153/ref=sr_1_2?s=books&ie=UTF8&qid=1291818936&sr=1-2"))
  end

end

