#!/usr/local/rvm/rubies/ruby-1.8.7-p334/bin/ruby
require "rubygems"
require 'mysql'
require 'find'
require "ap"
require "IO.rb"
require 'fileutils'

exit if ARGV[0] == nil

spoolDir      = "/usr/local/maximas2/spool"
spoolDirLog   = "/usr/local/maximas2/spool/logs"
wrongsprocDir = spoolDir # "/var/www/maxima/wrongsproc"
emailMatcher  = "^[A-Za-z0-9_%+-]+[A-Za-z0-9._%+-]*@[A-Za-z0-9.-]+\.[A-Za-z]{2,6}$"
spoolsArr     = []
@loggedMails  = []
@filename     = ""
@unsended     = []
@wrongMails   = []


## explicit remove
#Find.find(spoolDir) do |f|
##  File.delete(f) if f.match(/.#{ARGV[0]}.*/) && !f.match(/.new.*/)
#  if f.match(/.#{ARGV[0]}.*.\.sp.*/) # && !f.match(/.new.*/)
#    puts f
#    File.delete(f)
#  end
#end
#exit


def collectLoggedMails(file)
  IO.readlines(file).each do |line|
    @loggedMails.push line.split("\n")[0]
  end
end

Find.find(spoolDirLog) do |f|
  collectLoggedMails(f) if f.match(/.#{ARGV[0]}.*\.sp/)
end

def rename(file)
  newFile = "#{file.split(".")[0]}.spool"

  puts "rename: #{file}"
  puts "newFile: #{newFile}"

  File.rename(file, newFile)
end


def findError(file)
  @filename  = file.split("#{ARGV[0]}")[0]
  readFile(file.to_s).splitSproc
end

Find.find(wrongsprocDir) do |f|
  arr       = []
  arr << findError(f) if f.match(/.#{ARGV[0]}.*\.sp/)
  if arr != nil && arr.length > 0
    arr[0].each do |element|
      if element.match(/(\{email\}.)(.*)/)
        email = element.match(/(\{email\}.)(.*)/)[2]
        if email.match(/#{emailMatcher}/)
          @unsended.push email
          spoolsArr << "#{element}#{'{---separator---}'}\n" if !@loggedMails.include?(email)
        elsif
          if !@loggedMails.include?(email)
            @wrongMails.push(email)
          end
        end
      end
    end
  end
end



ap @unsended
puts "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx\n"
ap @wrongMails
puts "unsended: #{@unsended.length}"
puts "wrongMailslength: #{@wrongMails.length}"

# writing new spools
#iter    = 1
#content = ""
#spoolsArr.each do |spool|
#  content += spool
#  if iter % 10 == 0
#    file = "#{@filename}#{ARGV[0]}-#{iter}-new.tmp"
#    puts file
#    File.open(file,"w") do |f|
##      puts content
#      f.write(content)
#    end
#    rename(file)
#    content = ""
#  end
#  iter += 1
#end

## deleting the old ones
#Find.find(wrongsprocDir) do |f|
#  File.delete(f) if f.match(/.#{ARGV[0]}.*\.sproc/) && !f.match(/.new.*/)
#end

puts "DONE!"
