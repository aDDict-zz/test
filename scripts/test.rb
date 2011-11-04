#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby
require "rubygems"
require 'mysql'
require 'find'
require "ap"
require "IO.rb"
require 'fileutils'



r = fetchAll(@db.query("select count(*) as brutto from messages,user where messages.user_id=user.id and messages.test='no' and group_id=1150"))
allRecord = 0
allRecord = r[0]['brutto'] if r.length > 0

r         = fetchAll(@db.query("select brutto from affiliate_cache where user_id = 80121 and group_id = 1150 order by id desc limit 0,1"))
allCached = 0
allCached = r[0]['brutto'] if r.length > 0


puts "allRecord:  #{allRecord}"
puts "allCached:  #{allCached}"

puts "
  select
        messages.*,
        user.name
      from
        messages,
        user
      where
        messages.user_id=user.id
      and
        messages.test='no'
      and
        group_id='1150'
      order by
        create_date
          desc limit #{allCached},#{allRecord.to_i - allCached.to_i}
"

allMessage = fetchAll(@db.query("
      select
        messages.*,
        user.name
      from
        messages,
        user
      where
        messages.user_id=user.id
      and
        messages.test='no'
      and
        group_id='1150'
      order by
        create_date
          desc limit #{allCached},#{allRecord.to_i - allCached.to_i}
    "))

    ap allMessage


# users_mainap

#counter = 0
#begin
#  counter = fetchAll(@db.query("
#      select
#        count(*) as counter
#      from
#        users_#{title}
#      where
#        aff='#{user}'
#      and
#        messagelist like '%,#{message['id']},%'
#    "))[0]['counter']
#  rescue Mysql::Error
#end


#puts counter


#userGroups = fetchAll(@db.query("select * from members where membership = 'affiliate' || membership = 'moderator' || membership = 'owner' order by user_id"))
#i = 0
#userGroups.each do |userGroup|

##  if userGroup["user_id"] == '80121' && userGroup['group_id'] == '1150'
##    collectMessages(userGroup["user_id"], userGroup['group_id'])
##  end
##puts userGroup['id'] if userGroup['id'] == 385
##  collectMessages(userGroup["user_id"], userGroup['group_id'])
#puts userGroup['id']

#i += 1
#end

#puts i
