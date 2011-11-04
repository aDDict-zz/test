#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby
require "rubygems"
require 'mysql'
require 'find'
require "ap"
require "IO.rb"
require 'fileutils'

@db.query("set names 'utf8'");

def collectMessages(user, group)

  r         = fetchAll(@db.query("select title from groups where id = #{group}"))
  title     = r[0]['title'] if r.length > 0

  if title != ""

    allRecord = fetchAll(@db.query("select count(*) as brutto from messages,user where messages.user_id=user.id and messages.test='no' and group_id=#{group}"))[0]['brutto']
    # brutto is the allRecord of the last session
    r         = fetchAll(@db.query("select brutto from affiliate_cache order by id desc limit 0,1"))
    allCached = 0
    allCached = r[0]['brutto'] if r.length > 0

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
        group_id='#{group}
      order by
        create_date
          desc limit #{allCached},#{allRecord.to_i - allCached.to_i}'
    "))




    allMessage.each do |message|
      counter = 0
      begin
        # if the table does not exist
        counter = fetchAll(@db.query("
            select
              count(*) as counter
            from
              users_#{title}
            where
              aff='#{user}'
            and
              messagelist like '%,#{message['id']},%'
          "))[0]['counter']
        rescue Mysql::Error
      end

      if(counter.to_i > 0)

        puts "
          insert
            into
          affiliate_cache
            (name,subject,create_date,counter,group_id,user_id,brutto)
          values
            ('#{@db.escape_string(message['name'])}','#{@db.escape_string(message['subject'])}','#{message['create_date']}',#{counter},#{group},#{user},#{allRecord})
        "

        @db.query("
          insert
            into
          affiliate_cache
            (name,subject,create_date,counter,group_id,user_id,brutto)
          values
            ('#{@db.escape_string(message['name'])}','#{@db.escape_string(message['subject'])}','#{message['create_date']}',#{counter},#{group},#{user},#{allRecord})
        ")

      end
    end
  end
end

userGroups = fetchAll(@db.query("select * from members where membership = 'affiliate' order by user_id"))
userGroups.each do |userGroup|

#  if userGroup["user_id"] == '80121' && userGroup['group_id'] == '1150'
#    collectMessages(userGroup["user_id"], userGroup['group_id'])
#  end

  collectMessages(userGroup["user_id"], userGroup['group_id'])

end

writeFile("crontest", "mux")
